<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Models\Visit;
use App\Models\Slider;
use App\Models\Semestre;
use App\Models\Setting;
use App\Models\Matiere;
use App\Models\Page;
use App\Models\Ressource;
use App\Models\Publication;
use App\Models\Purchase;
use App\Models\DownloadHistory;
use App\Models\FinancialTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class HomeController extends Controller
{
    /** 1. ACCUEIL ET PAGES STATIQUES **/
    public function index(Request $request) {
        Visit::create(['ip_address' => $request->ip(), 'page_visited' => 'Accueil']);
        $sliders = Slider::orderBy('ordre')->get();
        $semestres = Semestre::withCount('matieres')->get();
        $settings = Setting::pluck('value', 'key');
        return view('welcome', compact('sliders', 'semestres', 'settings'));
    }

    public function showPage(Request $request, $slug) {
        $page = Page::where('slug', $slug)->firstOrFail();
        Visit::create(['ip_address' => $request->ip(), 'page_visited' => 'Page : ' . $page->titre]);
        $settings = Setting::pluck('value', 'key');
        return view('pages.show', compact('page', 'settings'));
    }

    /** 2. PARCOURS ACADÉMIQUE **/
    public function showSemestre(Request $request, $id) {
        $semestre = Semestre::with('matieres')->findOrFail($id);
        Visit::create(['ip_address' => $request->ip(), 'page_visited' => 'Semestre : ' . $semestre->nom]);
        return view('semestres.show', compact('semestre'));
    }

    public function showMatiere(Request $request, $id) {
        $matiere = Matiere::with(['ressources', 'semestre'])->findOrFail($id);
        Visit::create(['ip_address' => $request->ip(), 'page_visited' => 'Matière : ' . $matiere->nom]);
        return view('matieres.show', compact('matiere'));
    }

    public function library() {
    $publications = Publication::with('user')
        ->withAvg('ratings', 'stars') // Calcule la moyenne automatiquement
        ->withCount('ratings')        // Compte le nombre d'avis
        ->latest()
        ->paginate(12);

    $settings = Setting::pluck('value', 'key');
    return view('library.index', compact('publications', 'settings'));
}

    /** 3. SYSTÈME DE PAIEMENT UNIQUE (LIVRES & COURS) **/

    // Pour les Ressources (Cours/TD)
    public function checkout($id) {
        $item = Ressource::findOrFail($id);
        return view('payments.checkout', ['item' => $item, 'type' => 'ressource']);
    }

    // Pour les Publications (Livres/Mémoires)
    public function checkoutBook($id) {
        $item = Publication::findOrFail($id);
        return view('payments.checkout', ['item' => $item, 'type' => 'publication']);
    }

    // Confirmation d'achat universelle

   public function confirmPurchase(Request $request, $id)
{
    // 1. SÉCURITÉ ANTI-DOUBLON
    $existingPurchase = Purchase::where('payment_id', $request->payment_id)->first();
    if ($existingPurchase) {
        return redirect()->route('payment.thankyou')->with('error', 'Ce code a déjà été utilisé.');
    }

    // 2. RÉCUPÉRATION
    $type = $request->input('item_type');
    $item = ($type === 'publication') ? Publication::with('user')->findOrFail($id) : Ressource::with('user')->findOrFail($id);

    $isGuest = !auth()->check();
    $emailDest = $isGuest ? $request->guest_email : auth()->user()->email;
    $nomDest = $isGuest ? 'Cher utilisateur' : auth()->user()->name;

    // 3. ENREGISTREMENT
    $purchase = Purchase::create([
        'user_id' => auth()->id(),
        'guest_email' => $isGuest ? $request->guest_email : null,
        'ressource_id' => ($type === 'ressource') ? $id : null,
        'publication_id' => ($type === 'publication') ? $id : null,
        'amount' => $item->price,
        'payment_id' => $request->payment_id,
        'item_type' => $type,
        'status' => 'valide', // Validé direct car on suppose que PayTech/Wave/OM a marché
    ]);

    // 4. COMMISSIONS (Code inchangé...)
    if ($item->user && $item->user->user_type === 'teacher') {
        $item->user->increment('wallet_balance', $item->price * 0.70);
        // ... (Transaction financière) ...
    }

    // 5. ENVOI MAIL AVEC *DEUX* PIÈCES JOINTES
    try {
        // Charger les relations nécessaires pour le template
        $purchase->load(['ressource.user', 'publication.user', 'user']);

        $pdf = Pdf::loadView('invoices.template', compact('purchase'));

        // Chemin physique du fichier à envoyer
        $filePath = storage_path('app/public/' . $item->file_path);

        Mail::send([], [], function ($message) use ($emailDest, $nomDest, $item, $pdf, $filePath) {
            $email = $message
                    ->from(env('MAIL_FROM_ADDRESS', 'noreply@mio.sn'), env('MAIL_FROM_NAME', 'MIO Ressources'))
                    ->to($emailDest)
                    ->subject('✅ Votre commande est arrivée : ' . $item->titre)
                    ->html("<h2>Merci $nomDest !</h2>
                            <p>Voici votre commande MIO Ressources.</p>
                            <p>Vous trouverez ci-joint :</p>
                            <ul>
                                <li>Votre Facture (Preuve d'achat)</li>
                                <li><strong>Le Document acheté</strong> (PDF)</li>
                            </ul>
                            <p>Bonne lecture !</p>");

            // Pièce jointe 1 : La Facture
            $email->attachData($pdf->output(), 'Facture_MIO.pdf', ['mime' => 'application/pdf']);

            // Pièce jointe 2 : Le Document (Si c'est un fichier, pas une vidéo)
            if ($item->type !== 'Vidéo') {
                if (file_exists($filePath)) {
                    $email->attach($filePath);
                } else {
                    \Illuminate\Support\Facades\Log::warning("Fichier document non trouvé: $filePath");
                }
            }
        });

        \Illuminate\Support\Facades\Log::info("Email d'achat envoyé avec succès à: $emailDest");

    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error("Erreur Mail Achat : " . $e->getMessage() . " - " . $e->getFile() . ":" . $e->getLine());
        \Illuminate\Support\Facades\Log::error("Stack trace: " . $e->getTraceAsString());
    }

    // 6. REDIRECTION UNIVERSELLE VERS "MERCI"
    // On envoie tout le monde vers la page Merci, avec le lien de téléchargement direct en bonus
    $downloadLink = $isGuest
        ? URL::signedRoute('guest.download', ['token' => $purchase->payment_id, 'type' => $type, 'id' => $id])
        : route('ressource.download', $id);

    return redirect()->route('payment.thankyou')
        ->with('success', 'Paiement validé !')
        ->with('download_link', $downloadLink);
}

/**
 * Fonction privée pour envoyer les 3 mails sans ralentir le contrôleur principal
 */
private function sendTriangularEmails($emailAcheteur, $item, $pdfContent)
{
    try {
        $fromEmail = env('MAIL_FROM_ADDRESS');

        // Mail à l'Acheteur
        Mail::send([], [], function ($message) use ($emailAcheteur, $pdfContent, $item, $fromEmail) {
            $message->to($emailAcheteur)->from($fromEmail, 'MIO Ressources')
                    ->subject('✅ Votre document : ' . $item->titre)
                    ->attachData($pdfContent, 'facture.pdf')
                    ->html("<p>Merci ! Votre document <strong>{$item->titre}</strong> est disponible.</p>");
        });

        // Mail au Prof (si besoin)
        if ($item->user && $item->user->user_type === 'teacher') {
            Mail::send([], [], function ($message) use ($item, $pdfContent, $fromEmail) {
                $message->to($item->user->email)->from($fromEmail, 'MIO Système')
                        ->subject('💰 Nouvelle vente : ' . $item->titre)
                        ->attachData($pdfContent, 'avis_vente.pdf')
                        ->html("<p>Bravo ! Un étudiant a acheté votre ouvrage.</p>");
            });
        }
    } catch (\Exception $e) {
        \Log::error("Erreur Mail : " . $e->getMessage());
    }
}
    /** 4. ACCÈS ET SUPPORTS **/
    public function downloadRessource($id) {
        $ressource = Ressource::findOrFail($id);

        $currentUser = auth()->user();

        $isAdmin = $currentUser && ($currentUser->role ?? null) === 'admin';
        $isOwner = $currentUser && ($ressource->user_id ?? null) && $currentUser->id === $ressource->user_id;
        $isFree = !$ressource->is_premium;
        $hasPurchase = $currentUser && Purchase::where('user_id', $currentUser->id)
            ->where('ressource_id', $id)
            ->exists();

        // Si ressource premium et l'utilisateur ne l'a pas achetée -> rediriger vers paiement
        if ($ressource->is_premium && !$isAdmin && !$isOwner && !$hasPurchase) {
            return redirect()->route('ressource.checkout', $id);
        }

        // Accès autorisé si : admin OR propriétaire OR ressource gratuite OR achat existant
        if (!$isAdmin && !$isOwner && !$isFree && !$hasPurchase) {
            abort(403);
        }

        if ($currentUser) {
            DownloadHistory::updateOrCreate(
                ['user_id' => $currentUser->id, 'ressource_id' => $id],
                ['downloaded_at' => now()]
            );
        }

        return ($ressource->type === 'Vidéo')
            ? redirect()->away($ressource->file_path)
            : redirect()->to(asset('storage/' . $ressource->file_path));
    }

    public function guestDownload(Request $request, $token, $type, $id)
    {
        if (! $request->hasValidSignature()) {
            abort(403);
        }

        $purchase = Purchase::where('payment_id', $token)
            ->where('item_type', $type)
            ->when($type === 'publication', function ($q) use ($id) {
                $q->where('publication_id', $id);
            }, function ($q) use ($id) {
                $q->where('ressource_id', $id);
            })
            ->first();

        if (! $purchase) {
            abort(403);
        }

        $item = ($type === 'publication')
            ? Publication::findOrFail($id)
            : Ressource::findOrFail($id);

        if ($type === 'ressource' && $item->type === 'Vidéo') {
            return redirect()->away($item->file_path);
        }

        return redirect()->to(asset('storage/' . $item->file_path));
    }

     public function storeResourceRating(Request $request, $id, $type)
    {
        $request->validate([
            'stars' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        // On prépare les données
        $data = [
            'user_id' => auth()->id(),
            'stars' => $request->stars,
            'comment' => $request->comment,
        ];

        // On détermine si c'est un livre ou un cours
        if ($type === 'publication') {
            $search = ['user_id' => auth()->id(), 'publication_id' => $id];
            $data['publication_id'] = $id;
            $data['ressource_id'] = null; // Important pour éviter les conflits
        } else {
            $search = ['user_id' => auth()->id(), 'ressource_id' => $id];
            $data['ressource_id'] = $id;
            $data['publication_id'] = null;
        }

        // On enregistre ou on met à jour si l'avis existe déjà
        \App\Models\ResourceRating::updateOrCreate($search, $data);

        return back()->with('success', 'Votre avis a été publié avec succès !');
    }
}
