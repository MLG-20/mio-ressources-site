<?php

namespace App\Http\Controllers;

use App\Models\ForumSujet;
use App\Models\ForumMessage;
use App\Models\DownloadHistory; // <--- Import Important
use App\Models\Publication;
use App\Models\PrivateLessonEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserSpaceController extends Controller
{

    public function index() {
    $user = Auth::user();

    $mesSujets = ForumSujet::where('user_id', $user->id)->latest()->get();
    $mesMessages = ForumMessage::where('user_id', $user->id)->with('sujet')->latest()->get();
    $userMessages = ForumMessage::where('user_id', $user->id)->latest()->get();

    $purchasedIds = \App\Models\Purchase::where('user_id', $user->id)->pluck('ressource_id')->toArray();
    $historyIds = DownloadHistory::where('user_id', $user->id)->pluck('ressource_id')->toArray();
    $allIds = array_unique(array_merge($purchasedIds, $historyIds));

    $downloads = \App\Models\Ressource::whereIn('id', $allIds)->with('matiere')->get();

    // Récupérer les mémoires publiés par l'étudiant
    $mesMemoires = Publication::where('user_id', $user->id)->where('type', 'Mémoire')->latest()->get();

    // Récupérer les cours particuliers réservés par l'étudiant
    $mesCoursParticuliers = \App\Models\PrivateLessonEnrollment::with(['privateLesson' => function($query) {
        $query->with(['teacher', 'matiere']);
    }])
    ->where('student_id', $user->id)
    ->where('payment_status', 'paid') // Seulement les cours payés/confirmés
    ->orderBy('scheduled_at', 'asc')
    ->get();

    // ON RÉCUPÈRE LES NOUVEAUTÉS DE SON NIVEAU (Ex: L3)
    $nouveautes = \App\Models\Ressource::whereHas('matiere.semestre', function($query) use ($user) {
            $query->where('niveau', $user->student_level);
        })
        ->latest()
        ->take(5)
        ->get();

    return view('user.dashboard', compact('user', 'mesSujets', 'mesMessages', 'downloads', 'nouveautes', 'mesMemoires', 'userMessages', 'mesCoursParticuliers'));
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
        return redirect()->route('user.dashboard')->with('success', 'Profil mis à jour avec succès !')->withFragment('profil');
    }

    public function destroyMessage($id) {
        $message = ForumMessage::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $message->delete();
        return redirect()->route('user.dashboard')->with('success', 'Message supprimé.')->withFragment('messages');
    }

    public function destroySujet($id) {
        $sujet = ForumSujet::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $sujet->delete();
        return back()->with('success', 'Discussion supprimée.');
    }

    public function deleteAccount(Request $request) {
        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', 'Votre compte a été supprimé définitivement.');
    }

    // Supprimer une entrée de l'historique
public function destroyHistory($id) {
    DownloadHistory::where('id', $id)->where('user_id', Auth::id())->delete();
    return redirect()->route('user.dashboard')->with('success', 'Document retiré de l\'historique.')->withFragment('historique');
}

// Vider tout l'historique
public function clearHistory() {
    DownloadHistory::where('user_id', Auth::id())->delete();
    return back()->with('success', 'Historique vidé.');
}

public function publishMemoir(Request $request) {
    $request->validate([
        'titre' => 'required|string|max:255',
        'file_path' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,7z,txt,jpg,jpeg,png|max:51200',
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

    return back()->with('success', 'Votre mémoire a été publié dans la bibliothèque.');
}

public function destroyMemoir($id) {
    $publication = Publication::findOrFail($id);

    // Vérifier que l'utilisateur est propriétaire
    if ($publication->user_id !== Auth::id()) {
        abort(403, 'Non autorisé.');
    }

    // Vérifier que c'est bien un mémoire
    if ($publication->type !== 'Mémoire') {
        abort(403, 'Non autorisé.');
    }

    // Supprimer les fichiers
    if ($publication->file_path && Storage::disk('public')->exists($publication->file_path)) {
        Storage::disk('public')->delete($publication->file_path);
    }
    if ($publication->cover_image && Storage::disk('public')->exists($publication->cover_image)) {
        Storage::disk('public')->delete($publication->cover_image);
    }

    $publication->delete();
    return redirect()->route('user.dashboard')->with('success', 'Mémoire supprimé de la bibliothèque.')->withFragment('historique');
}

    /**
     * Créer un message direct depuis l'espace étudiant
     */
    public function storeMessage(Request $request) {
        $request->validate([
            'contenu' => 'required|string|min:3|max:1000',
        ]);

        ForumMessage::create([
            'contenu' => $request->contenu,
            'user_id' => Auth::id(),
            'forum_sujet_id' => null, // Message standalone
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Message partagé avec succès !')->withFragment('messages');
    }

    /**
     * Mettre à jour un message
     */
    public function updateMessage(Request $request, $id) {
        $message = ForumMessage::findOrFail($id);

        // Vérifier que l'utilisateur est propriétaire
        if ($message->user_id !== Auth::id()) {
            abort(403, 'Non autorisé.');
        }

        $request->validate([
            'contenu' => 'required|string|min:3|max:1000',
        ]);

        $message->update([
            'contenu' => $request->contenu,
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Message mis à jour !')->withFragment('messages');
    }
}
