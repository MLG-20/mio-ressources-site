<?php

namespace App\Http\Controllers;

use App\Models\ForumSujet;
use App\Models\ForumMessage;
use App\Models\ForumCategory;
use App\Models\DownloadHistory;
use App\Models\Publication;
use App\Models\PrivateLessonEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserSpaceController extends Controller
{
    // --- FONCTION PRIVÉE POUR NETTOYER LE NIVEAU (ANTI-DOUBLON) ---
    private function getCleanLevel($level) {
        if (!$level) return 'Général';

        // 1. On nettoie (majuscule et sans espace)
        $clean = strtoupper(trim($level));

        // 2. TABLEAU DE CORRESPONDANCE (Mapping)
        // Si le système trouve "L3", il le transforme en "Licence 3"
        $mapping = [
            'L1' => 'Licence 1',
            'L2' => 'Licence 2',
            'L3' => 'Licence 3',
            'M1' => 'Master 1',
            'M2' => 'Master 2',
        ];

        // Si on trouve la correspondance, on la retourne. Sinon on retourne le texte original.
        return $mapping[$clean] ?? ucfirst(trim($level));
    }

    public function index() {
        $user = Auth::user();

        // --- 1. LOGIQUE FORUM AUTOMATIQUE (PAR CLASSE) ---
        $niveau = $this->getCleanLevel($user->student_level);

        $categorieClasse = ForumCategory::firstOrCreate(
            ['nom' => $niveau],
            ['description' => "Espace de discussion pour les étudiants de $niveau"]
        );

        $sujetsDeMaClasse = ForumSujet::where('forum_category_id', $categorieClasse->id)
                            ->with('user')
                            ->latest()
                            ->take(20)
                            ->get();

        // --- 2. AUTRES DONNÉES DU DASHBOARD ---

        // vvvvvvvvvvvv C'EST CETTE LIGNE QUI MANQUAIT vvvvvvvvvvvv
        $mesSujets = ForumSujet::where('user_id', $user->id)->latest()->get();
        // ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

        $mesMessages = ForumMessage::where('user_id', $user->id)->with('sujet')->latest()->get();
        $userMessages = ForumMessage::where('user_id', $user->id)->latest()->get();

        $purchasedIds = \App\Models\Purchase::where('user_id', $user->id)->pluck('ressource_id')->toArray();
        $historyIds = DownloadHistory::where('user_id', $user->id)->pluck('ressource_id')->toArray();
        $allIds = array_unique(array_merge($purchasedIds, $historyIds));

        $downloads = \App\Models\Ressource::whereIn('id', $allIds)->with('matiere')->get();
        $mesMemoires = Publication::where('user_id', $user->id)->where('type', 'Mémoire')->latest()->get();

        $mesCoursParticuliers = PrivateLessonEnrollment::with(['privateLesson.teacher', 'privateLesson.matiere'])
            ->where('student_id', $user->id)
            ->where('payment_status', 'paid')
            ->orderBy('scheduled_at', 'asc')
            ->get();

        $nouveautes = \App\Models\Ressource::whereHas('matiere.semestre', function($query) use ($user) {
                $query->where('niveau', $user->student_level);
            })->latest()->take(5)->get();

        return view('user.dashboard', compact(
            'user', 'mesSujets', 'mesMessages', 'downloads', 'nouveautes',
            'mesMemoires', 'userMessages', 'mesCoursParticuliers', 'sujetsDeMaClasse'
        ));
    
    }

    /**
     * POSTER UN MESSAGE (AVEC SÉCURITÉ ANTI-DOUBLON)
     */
    public function storeMessage(Request $request) {
        $user = Auth::user();

        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string|min:3|max:1000',
        ]);

        // UTILISATION DE LA SÉCURITÉ ANTI-DOUBLON ICI AUSSI
        $niveau = $this->getCleanLevel($user->student_level);

        // On récupère exactement la même catégorie que dans index()
        $categorie = ForumCategory::firstOrCreate(
            ['nom' => $niveau],
            ['description' => "Discussion niveau $niveau"]
        );

        // Création
        $sujet = ForumSujet::create([
            'titre' => $request->titre,
            'user_id' => $user->id,
            'forum_category_id' => $categorie->id,
        ]);

        ForumMessage::create([
            'contenu' => $request->contenu,
            'user_id' => $user->id,
            'forum_sujet_id' => $sujet->id,
        ]);

        return redirect()->route('user.dashboard')
            ->with('success', "Discussion lancée dans l'espace $niveau !")
            ->withFragment('messages');
    }

    // --- VOS AUTRES FONCTIONS RESTENT INCHANGÉES ---

    public function destroySujet($id) {
        $sujet = ForumSujet::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $sujet->messages()->delete();
        $sujet->delete();
        return back()->with('success', 'Discussion supprimée.');
    }

    public function updateProfile(Request $request) {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'avatar' => 'nullable|image|max:2048',
            'current_password' => 'nullable|required_with:new_password|current_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->hasFile('avatar')) {
            if ($user->avatar) { Storage::disk('public')->delete($user->avatar); }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }
        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }
        $user->save();
        return redirect()->route('user.dashboard')->with('success', 'Profil mis à jour !')->withFragment('profil');
    }

    public function destroyMessage($id) {
        $message = ForumMessage::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $message->delete();
        return redirect()->route('user.dashboard')->with('success', 'Message supprimé.')->withFragment('messages');
    }

    public function updateMessage(Request $request, $id) {
        $message = ForumMessage::findOrFail($id);
        if ($message->user_id !== Auth::id()) { abort(403); }
        $request->validate(['contenu' => 'required|string|min:3|max:1000']);
        $message->update(['contenu' => $request->contenu]);
        return redirect()->route('user.dashboard')->with('success', 'Message mis à jour !')->withFragment('messages');
    }

    public function deleteAccount(Request $request) {
        $user = Auth::user();
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Mot de passe incorrect.']);
        }
        Auth::logout();
        $user->delete();
        return redirect('/')->with('success', 'Compte supprimé.');
    }

    public function destroyHistory($id) {
        DownloadHistory::where('id', $id)->where('user_id', Auth::id())->delete();
        return redirect()->route('user.dashboard')->with('success', 'Document retiré.')->withFragment('historique');
    }

    public function clearHistory() {
        DownloadHistory::where('user_id', Auth::id())->delete();
        return back()->with('success', 'Historique vidé.');
    }

    public function publishMemoir(Request $request) {
        $request->validate([
            'titre' => 'required|string|max:255',
            'file_path' => 'required|file|mimes:pdf|max:51200',
            'cover_image' => 'nullable|image|max:2048',
        ]);
        $file = $request->file('file_path')->store('publications', 'public');
        $cover = $request->hasFile('cover_image') ? $request->file('cover_image')->store('covers', 'public') : null;
        Publication::create([
            'titre' => $request->titre,
            'type' => 'Mémoire',
            'description' => $request->description,
            'file_path' => $file,
            'cover_image' => $cover,
            'user_id' => Auth::id(),
            'is_premium' => false,
            'price' => 0,
            'is_verified' => true,
        ]);
        return back()->with('success', 'Mémoire publié.');
    }

    public function destroyMemoir($id) {
        $publication = Publication::findOrFail($id);
        if ($publication->user_id !== Auth::id()) { abort(403); }
        if ($publication->file_path) Storage::disk('public')->delete($publication->file_path);
        if ($publication->cover_image) Storage::disk('public')->delete($publication->cover_image);
        $publication->delete();
        return redirect()->route('user.dashboard')->with('success', 'Mémoire supprimé.');
    }
}
