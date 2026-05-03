<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ressource;
use App\Models\Publication;
use App\Models\Purchase;
use App\Models\FinancialTransaction;
use App\Models\DownloadHistory;
use App\Models\SubscriptionPayment;
use App\Models\User;
use App\Jobs\SendPurchaseInvoiceJob;
use App\Jobs\SendSubscriptionConfirmationJob;
use App\Notifications\PurchaseInvoiceNotification;
use Illuminate\Http\Client\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private const STUDENT_SUBSCRIPTION_PRICE = 500;

    private const SUBSCRIPTION_PLANS = [
        'monthly'   => ['months' => 1,  'price' => 500,  'label' => 'Mensuel (1 mois)'],
        'quarterly' => ['months' => 3,  'price' => 1200, 'label' => 'Trimestriel (3 mois)'],
        'annual'    => ['months' => 12, 'price' => 4000, 'label' => 'Annuel (12 mois)'],
    ];

    // 1. DÉMARRAGE DU PAIEMENT
    public function initiatePayment(Request $request, $id, $type)
    {
        $item = ($type === 'publication') ? Publication::findOrFail($id) : Ressource::findOrFail($id);
        /** @var User|null $user */
        $user = Auth::user();

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
        $ipnSecret = (string) config('services.paytech.ipn_secret', '');
        if ($ipnSecret === '') {
            Log::error('PAYTECH_IPN_SECRET is not configured. IPN webhook security is not enforced.');
        }

        $ipnUrl = route('payment.ipn');
        if ($ipnSecret !== '') {
            $separator = str_contains($ipnUrl, '?') ? '&' : '?';
            $ipnUrl .= $separator . http_build_query(['secret' => $ipnSecret]);
        }

        // Appel API PayTech
        $apiKey = trim((string) config('services.paytech.api_key'));
        $apiSecret = trim((string) config('services.paytech.api_secret'));
        $paytechEnv = trim((string) config('services.paytech.env', 'test'));

        /** @var HttpResponse $response */
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

    // 1.b DÉMARRAGE DU PAIEMENT ABONNEMENT ÉTUDIANT
    public function initiateStudentSubscription(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! ($user instanceof User) || $user->user_type !== 'student') {
            abort(403);
        }

        $planKey = $request->input('plan', 'monthly');
        $plan = self::SUBSCRIPTION_PLANS[$planKey] ?? self::SUBSCRIPTION_PLANS['monthly'];

        $ipnSecret = (string) config('services.paytech.ipn_secret', '');
        if ($ipnSecret === '') {
            Log::error('PAYTECH_IPN_SECRET is not configured. IPN webhook security is not enforced.');
        }

        $ipnUrl = route('payment.ipn');
        if ($ipnSecret !== '') {
            $separator = str_contains($ipnUrl, '?') ? '&' : '?';
            $ipnUrl .= $separator . http_build_query(['secret' => $ipnSecret]);
        }

        $apiKey = trim((string) config('services.paytech.api_key'));
        $apiSecret = trim((string) config('services.paytech.api_secret'));
        $paytechEnv = trim((string) config('services.paytech.env', 'test'));

        /** @var HttpResponse $response */
        $response = Http::asJson()
            ->withOptions([
                'verify' => ! app()->environment('local'),
            ])
            ->withHeaders([
                'API_KEY' => $apiKey,
                'API_SECRET' => $apiSecret,
                'Content-Type' => 'application/json',
            ])
            ->post('https://paytech.sn/api/payment/request-payment', [
                'item_name' => 'Abonnement étudiant MIO',
                'item_price' => $plan['price'],
                'currency' => 'XOF',
                'ref_command' => uniqid('MIO-SUB-'),
                'command_name' => 'Abonnement étudiant MIO — ' . $plan['label'],
                'env' => $paytechEnv,
                'ipn_url' => $ipnUrl,
                'success_url' => route('student.subscription.success'),
                'cancel_url' => route('student.subscription.paywall'),
                'custom_field' => json_encode([
                    'payment_kind' => 'student_subscription',
                    'user_id' => $user->id,
                    'months' => $plan['months'],
                ]),
            ]);

        $data = $response->json();

        if ($response->successful() && isset($data['success']) && (int) $data['success'] === 1 && isset($data['redirect_url'])) {
            session(['paytech_subscription_token' => $data['token'] ?? null]);

            return redirect($data['redirect_url']);
        }

        Log::warning('PayTech student subscription initiate failed', [
            'status' => $response->status(),
            'body' => $data,
            'user_id' => $user->id,
        ]);

        return back()->with('error', 'Paiement indisponible pour le moment. Veuillez réessayer.');
    }

    // 2. RETOUR SUCCÈS
    public function success($id, $type)
    {
        if (Auth::check()) {
            return redirect()->route('user.dashboard')->with('success', 'Paiement validé ! Votre document et votre facture ont été envoyés à votre adresse email. Pensez à vérifier vos spams.');
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

    public function studentSubscriptionSuccess()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! ($user instanceof User) || $user->user_type !== 'student') {
            return redirect()->route('home');
        }

        return redirect()
            ->route('user.dashboard')
            ->with('success', 'Abonnement étudiant activé avec succès. Un email de confirmation vous a été envoyé.');
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
        $expectedSecret = trim((string) config('services.paytech.ipn_secret', ''));
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

        $apiKey = trim((string) config('services.paytech.api_key', ''));
        $apiSecret = trim((string) config('services.paytech.api_secret', ''));

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
            Log::warning('PayTech IPN invalid custom_field', ['ip' => $request->ip(), 'has_token' => $request->has('token')]);
            return response()->json(['success' => 'invalid'], 400);
        }

        if (!isset($request->token)) {
            Log::warning('PayTech IPN missing token', ['ip' => $request->ip(), 'fields' => array_keys($request->all())]);
            return response()->json(['success' => 'invalid'], 400);
        }

        $isSubscriptionPayment = ($customData['payment_kind'] ?? null) === 'student_subscription';

        if (! $isSubscriptionPayment && (!isset($customData['item_id'], $customData['item_type']))) {
            Log::warning('PayTech IPN missing required fields', ['ip' => $request->ip(), 'fields' => array_keys($request->all())]);
            return response()->json(['success' => 'invalid'], 400);
        }

        if ($isSubscriptionPayment) {
            $this->activateStudentSubscription(
                (int) ($customData['user_id'] ?? 0),
                (int) ($customData['months'] ?? 1),
                (string) $request->input('token')
            );

            return response()->json(['success' => 'ok']);
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
            /** @var HttpResponse $statusResponse */
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

        SendPurchaseInvoiceJob::dispatch($purchase, $userId, $guestEmail, $type, $itemId, $ref);
    }

    private function activateStudentSubscription(int $userId, int $months, string $paymentId): void
    {
        if ($userId <= 0 || $paymentId === '') {
            return;
        }

        if (SubscriptionPayment::where('payment_id', $paymentId)->exists()) {
            return;
        }

        $user = User::find($userId);
        if (! $user || $user->user_type !== 'student') {
            return;
        }

        $months = max(1, $months);
        $baseDate = $user->subscription_paid_until && $user->subscription_paid_until->isFuture()
            ? $user->subscription_paid_until->copy()
            : now();

        $newPaidUntil = $baseDate->addMonths($months);

        $user->subscription_paid_until = $newPaidUntil;
        $user->is_blocked = false; // Important: ne pas garder un étudiant verrouillé par erreur.
        $user->save();

        SubscriptionPayment::create([
            'user_id' => $user->id,
            'amount' => self::STUDENT_SUBSCRIPTION_PRICE * $months,
            'months' => $months,
            'payment_id' => $paymentId,
            'provider' => 'paytech',
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        SendSubscriptionConfirmationJob::dispatch($user, $newPaidUntil);
    }
}
