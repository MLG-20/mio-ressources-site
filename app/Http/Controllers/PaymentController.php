<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ressource;
use App\Models\Publication;
use App\Models\Purchase;
use App\Models\FinancialTransaction;
use App\Models\DownloadHistory;
use App\Models\User;
use App\Notifications\PurchaseInvoiceNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    // 1. DÉMARRAGE DU PAIEMENT
    public function initiatePayment(Request $request, $id, $type)
    {
        $item = ($type === 'publication') ? Publication::findOrFail($id) : Ressource::findOrFail($id);
        $user = auth()->user();

        // SAUVEGARDE DE L'EMAIL VISITEUR EN SESSION (C'est la clé !)
        if (!$user) {
            $request->validate([
                'guest_email' => ['required', 'email'],
            ]);

            session(['guest_email' => $request->guest_email]);
        }

        // SÉCURITÉ (IPN) :
        // Le webhook PayTech (/paiement/ipn) est public et exempté de CSRF (normal).
        // On ajoute donc un secret applicatif (PAYTECH_IPN_SECRET) dans l'URL IPN,
        // puis on le vérifie dans handleIPN().
        $ipnSecret = (string) env('PAYTECH_IPN_SECRET', '');
        if ($ipnSecret === '') {
            Log::error('PAYTECH_IPN_SECRET is not configured. IPN webhook security is not enforced.');
        }

        $ipnUrl = route('payment.ipn');
        if ($ipnSecret !== '') {
            $separator = str_contains($ipnUrl, '?') ? '&' : '?';
            $ipnUrl .= $separator . http_build_query(['secret' => $ipnSecret]);
        }

        // Appel API PayTech
        $apiKey = trim((string) env('PAYTECH_API_KEY'));
        $apiSecret = trim((string) env('PAYTECH_API_SECRET'));
        $paytechEnv = trim((string) env('PAYTECH_ENV', 'test'));

        $response = Http::asJson()
            ->withOptions([
                'verify' => ! app()->environment('local'),
            ])
            ->withHeaders([
                'API_KEY' => $apiKey,
                'API_SECRET' => $apiSecret,
                'Content-Type' => 'application/json'
            ])
            ->post('https://paytech.sn/api/payment/request-payment', [
                'item_name' => $item->titre,
                'item_price' => $item->price,
                'currency' => 'XOF',
                'ref_command' => uniqid('MIO-'),
                'command_name' => "Achat MIO : " . $item->titre,
                'env' => $paytechEnv,
                'ipn_url' => $ipnUrl,
                'success_url' => route('payment.success', ['id' => $id, 'type' => $type]),
                'cancel_url' => route('home'), // Retour accueil si annulation
                'custom_field' => json_encode([
                    'user_id' => $user ? $user->id : null,
                    'guest_email' => $user ? null : $request->guest_email,
                    'item_id' => $id,
                    'item_type' => $type,
                ]),
            ]);

        $data = $response->json();

        if ($response->successful() && isset($data['success']) && $data['success'] == 1 && isset($data['redirect_url'])) {
            if (!$user && isset($data['token'])) {
                session([
                    'paytech_last_token' => $data['token'],
                    'paytech_last_item_id' => $id,
                    'paytech_last_item_type' => $type,
                ]);
            }
            return redirect($data['redirect_url']);
        }

        Log::warning('PayTech initiatePayment failed', [
            'status' => $response->status(),
            'body' => $data,
            'item_id' => $id,
            'item_type' => $type,
            'user_id' => $user?->id,
        ]);

        return back()->with('error', 'Paiement indisponible pour le moment. Veuillez réessayer.');
    }

    // 2. RETOUR SUCCÈS
    public function success($id, $type)
    {
        // En local, on force la validation
        if (app()->environment('local')) {
            // On récupère l'email sauvegardé en session
            $guestEmail = session('guest_email');
            $userId = auth()->id();

            $this->validateOrder($userId, $guestEmail, $id, $type, 'TEST-' . uniqid());
        }

        if (auth()->check()) {
            return redirect()->route('user.dashboard')->with('success', 'Paiement validé !');
        }

        $token = (string) session('paytech_last_token', '');
        $lastType = (string) session('paytech_last_item_type', '');
        $lastId = (int) session('paytech_last_item_id', 0);

        if ($token !== '' && $lastType === $type && $lastId === (int) $id) {
            $downloadLink = URL::temporarySignedRoute(
                'guest.download',
                now()->addMinutes(30),
                ['token' => $token, 'type' => $type, 'id' => $id]
            );

            return redirect()->route('payment.thankyou')->with('download_link', $downloadLink);
        }

        return redirect()->route('payment.thankyou');
    }

    // 3. IPN (Webhook Production)
    public function handleIPN(Request $request)
    {
        $forbidden = function (string $reason) {
            return response()->json(['success' => 'forbidden', 'reason' => $reason], 403);
        };

        // SÉCURITÉ (IPN) : vérification d'un secret applicatif.
        // Cela évite qu'un tiers appelle /paiement/ipn et valide une commande arbitrairement.
        // Note: on trim pour éviter les problèmes de CRLF/espaces dans le .env.
        $expectedSecret = trim((string) env('PAYTECH_IPN_SECRET', ''));
        $providedSecret = trim((string) $request->query('secret', ''));

        // Si le secret n'est pas configuré, on préfère bloquer en production.
        if ($expectedSecret === '') {
            Log::error('PAYTECH_IPN_SECRET missing: refusing IPN request.');
            return $forbidden('config_missing_ipn_secret');
        }

        // Comparaison constante (évite les attaques timing).
        if (! hash_equals($expectedSecret, $providedSecret)) {
            Log::warning('PayTech IPN forbidden (invalid secret)', [
                'ip' => $request->ip(),
                // SÉCURITÉ : on ne loggue jamais le secret, uniquement les longueurs.
                'expected_len' => strlen($expectedSecret),
                'provided_len' => strlen($providedSecret),
            ]);
            return $forbidden('invalid_secret');
        }

        // SÉCURITÉ (IPN PayTech) : vérifier que la requête provient bien de PayTech.
        // D'après la documentation PayTech : l'IPN contient api_key_sha256 et api_secret_sha256.
        // On compare ces hashes aux hashes calculés à partir de nos clés.
        $apiKeySha256Provided = (string) $request->input('api_key_sha256', '');
        $apiSecretSha256Provided = (string) $request->input('api_secret_sha256', (string) $request->input('api_secret_sha2566', ''));

        $apiKey = trim((string) env('PAYTECH_API_KEY', ''));
        $apiSecret = trim((string) env('PAYTECH_API_SECRET', ''));

        // Si la config n'est pas présente, on bloque (évite d'accepter un IPN non vérifiable).
        if ($apiKey === '' || $apiSecret === '') {
            Log::error('PayTech IPN verification failed: PAYTECH_API_KEY/PAYTECH_API_SECRET missing.');
            return $forbidden('config_missing_api_keys');
        }

        $apiKeySha256Expected = hash('sha256', $apiKey);
        $apiSecretSha256Expected = hash('sha256', $apiSecret);

        if ($apiKeySha256Provided === '' || $apiSecretSha256Provided === '') {
            Log::warning('PayTech IPN forbidden (missing sha256 fields)', [
                'ip' => $request->ip(),
            ]);
            return $forbidden('sha_missing');
        }

        if (! hash_equals($apiKeySha256Expected, $apiKeySha256Provided) || ! hash_equals($apiSecretSha256Expected, $apiSecretSha256Provided)) {
            Log::warning('PayTech IPN forbidden (sha256 mismatch)', [
                'ip' => $request->ip(),
                // SÉCURITÉ : on ne loggue jamais les clés, seulement les tailles.
                'api_key_sha256_len' => strlen($apiKeySha256Provided),
                'api_secret_sha256_len' => strlen($apiSecretSha256Provided),
            ]);
            return $forbidden('sha_mismatch');
        }

        $customData = json_decode((string) $request->custom_field, true);

        if (!is_array($customData)) {
            Log::warning('PayTech IPN invalid custom_field', ['payload' => $request->all()]);
            return response()->json(['success' => 'invalid'], 400);
        }

        if (!isset($customData['item_id'], $customData['item_type']) || !isset($request->token)) {
            Log::warning('PayTech IPN missing required fields', ['payload' => $request->all()]);
            return response()->json(['success' => 'invalid'], 400);
        }

        // SÉCURITÉ / ROBUSTESSE : refuser si l'item référencé n'existe pas.
        $itemExists = $customData['item_type'] === 'publication'
            ? Publication::whereKey($customData['item_id'])->exists()
            : Ressource::whereKey($customData['item_id'])->exists();

        if (! $itemExists) {
            Log::warning('PayTech IPN unknown item', [
                'item_id' => $customData['item_id'],
                'item_type' => $customData['item_type'],
            ]);
            return response()->json(['success' => 'invalid'], 400);
        }

        // SÉCURITÉ : validation serveur→serveur du token PayTech.
        // On appelle l'API de statut afin d'éviter qu'un token arbitraire soit accepté.
        $token = (string) $request->input('token');
        $refCommandFromIpn = (string) $request->input('ref_command', '');

        try {
            $statusResponse = Http::timeout(10)
                ->withOptions([
                    'verify' => ! app()->environment('local'),
                ])
                ->withHeaders([
                    'API_KEY' => $apiKey,
                    'API_SECRET' => $apiSecret,
                ])
                ->get('https://paytech.sn/api/payment/get-status', [
                    'token_payment' => $token,
                ]);

            $statusData = $statusResponse->json();

            if (! $statusResponse->successful() || !isset($statusData['success']) || (int) $statusData['success'] !== 1) {
                Log::warning('PayTech IPN forbidden (get-status failed)', [
                    'ip' => $request->ip(),
                    'http_status' => $statusResponse->status(),
                    'token_len' => strlen($token),
                ]);
                return $forbidden('get_status_failed');
            }

            $payment = $statusData['payment'] ?? null;
            $state = is_array($payment) ? (string) ($payment['state'] ?? '') : '';
            $refCommandFromStatus = is_array($payment) ? (string) ($payment['ref_command'] ?? '') : '';

            // Dans ton test get-status : state="running" (non payé). On n'accepte que success.
            if ($state !== 'success') {
                Log::info('PayTech IPN ignored (payment not successful yet)', [
                    'state' => $state,
                    'ref_command' => $refCommandFromStatus,
                ]);
                return response()->json(['success' => 'ignored'], 202);
            }

            // Robustesse : si PayTech renvoie ref_command, on vérifie la cohérence avec l'IPN.
            if ($refCommandFromIpn !== '' && $refCommandFromStatus !== '' && $refCommandFromIpn !== $refCommandFromStatus) {
                Log::warning('PayTech IPN forbidden (ref_command mismatch)', [
                    'ip' => $request->ip(),
                ]);
                return $forbidden('ref_command_mismatch');
            }
        } catch (\Throwable $e) {
            Log::error('PayTech IPN verification error (get-status exception)', [
                'message' => $e->getMessage(),
            ]);
            return $forbidden('get_status_exception');
        }

        $this->validateOrder($customData['user_id'] ?? null, $customData['guest_email'] ?? null, $customData['item_id'], $customData['item_type'], $token);

        return response()->json(['success' => 'ok']);
    }

    // 4. MOTEUR DE VALIDATION ET ENVOI DE MAIL
    private function validateOrder($userId, $guestEmail, $itemId, $type, $ref)
    {
        if (Purchase::where('payment_id', $ref)->exists()) return;

        $item = ($type === 'publication') ? Publication::find($itemId) : Ressource::find($itemId);

        // ROBUSTESSE : éviter une erreur 500 si l'item n'existe pas (IPN malformé / id invalide).
        if (! $item) {
            Log::warning('validateOrder aborted: item not found', [
                'item_id' => $itemId,
                'item_type' => $type,
                'payment_id' => $ref,
            ]);
            return;
        }

        // Enregistrement Achat
        $purchase = Purchase::create([
            'user_id' => $userId,
            'guest_email' => $guestEmail, // On enregistre l'email invité
            'ressource_id' => ($type === 'ressource') ? $itemId : null,
            'publication_id' => ($type === 'publication') ? $itemId : null,
            'amount' => $item->price,
            'payment_id' => $ref,
            'status' => 'valide',
            'item_type' => $type,
        ]);

        // Envoyer la notification de facture si c'est un utilisateur enregistré
        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                $user->notify(new PurchaseInvoiceNotification($purchase));
            }
        };

        // Commissions Prof
        if ($item->user && $item->user->user_type === 'teacher') {
            $partProf = $item->price * 0.70;
            $item->user->increment('wallet_balance', $partProf);
            FinancialTransaction::create([
                'user_id' => $item->user->id,
                'type' => 'CREDIT_VENTE',
                'amount' => $partProf,
                'reference' => $ref,
                'description' => "Vente PayTech : " . $item->titre
            ]);
        }

        // Historique (si connecté)
        if ($userId && $type === 'ressource') {
            DownloadHistory::updateOrCreate(['user_id' => $userId, 'ressource_id' => $item->id], ['downloaded_at' => now()]);
        }

        // --- ENVOI DU MAIL AVEC PDF ---
        try {
            // Charger les relations pour le PDF
            $purchase->load(['user', 'ressource.user', 'publication.user']);

            if (app()->environment('local')) {
                return;
            }

            $pdf = Pdf::loadView('invoices.template', compact('purchase'));
            $pdfContent = $pdf->output();

            // Déterminer l'email destinataire
            $user = $userId ? \App\Models\User::find($userId) : null;
            $destinataire = $user ? $user->email : $guestEmail;

            $downloadLink = null;
            if (! $user && $guestEmail) {
                $downloadLink = URL::temporarySignedRoute(
                    'guest.download',
                    now()->addHours(24),
                    ['token' => $ref, 'type' => $type, 'id' => $itemId]
                );
            }

            if ($destinataire) {
                Mail::send([], [], function ($message) use ($destinataire, $pdfContent, $item, $downloadLink) {
                    $message->to($destinataire)
                            ->subject('✅ Votre commande MIO : ' . $item->titre)
                            ->attachData($pdfContent, 'Facture_MIO.pdf', ['mime' => 'application/pdf'])
                            ->html(
                                $downloadLink
                                    ? ("<h2>Merci !</h2><p>Votre paiement a été validé.</p><p><a href=\"" . e($downloadLink) . "\">Télécharger votre document</a> (lien valable 24h)</p><p>Voici votre facture en pièce jointe.</p>")
                                    : ("<h2>Merci !</h2><p>Voici votre document et votre facture.</p>")
                            );

                    // Si c'est un fichier local, on l'attache aussi
                    if (file_exists(storage_path('app/public/' . $item->file_path))) {
                         $message->attach(storage_path('app/public/' . $item->file_path));
                    }
                });
            }
        } catch (\Exception $e) {
            Log::error("Erreur Mail : " . $e->getMessage());
        }
    }
}
