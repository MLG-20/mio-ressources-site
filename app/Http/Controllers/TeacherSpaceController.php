<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\ForumSujet;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TeacherSpaceController extends Controller
{
    public function index() {
        $user = Auth::user();
        $mesPublications = Publication::where('user_id', $user->id)->latest()->get();
        $recentSujets = ForumSujet::with(['user', 'category'])->latest()->paginate(10);
        $totalVues = 0; // À lier avec tes analytics
        $totalRevenus = $user->wallet_balance;
        $meetings = Meeting::where('user_id', $user->id)->orderBy('scheduled_at', 'desc')->get();

        return view('teacher.dashboard', compact('user', 'mesPublications', 'recentSujets', 'totalRevenus', 'totalVues', 'meetings'));
    }

    public function updateProfile(Request $request) {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'specialty' => 'nullable|string|max:100',
            'avatar' => 'nullable|image|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // Mise à jour des infos
        $user->name = $request->name;
        $user->email = $request->email;
        $user->specialty = $request->specialty;

        if ($request->hasFile('avatar')) {
            if ($user->avatar) { Storage::disk('public')->delete($user->avatar); }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'L\'ancien mot de passe est incorrect.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();
        return back()->with('success', 'Votre profil a été mis à jour avec succès.');
    }

    public function deleteAccount(Request $request) {
        $user = Auth::user();
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['del_password' => 'Mot de passe incorrect.']);
        }
        Auth::logout();
        $user->delete();
        return redirect('/')->with('success', 'Compte supprimé.');
    }

    public function store(Request $request) {
        $request->validate([
            'titre' => 'required|string|max:255',
            'type' => 'required',
            'file_path' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,7z,txt,jpg,jpeg,png|max:51200',
        ]);

        $file = $request->file('file_path')->store('publications', 'public');
        $cover = $request->hasFile('cover_image') ? $request->file('cover_image')->store('covers', 'public') : null;

        Publication::create([
            'titre' => $request->titre,
            'type' => $request->type,
            'description' => $request->description,
            'file_path' => $file,
            'cover_image' => $cover,
            'user_id' => Auth::id(),
            'is_premium' => $request->has('is_premium'),
            'price' => $request->has('is_premium') ? $request->price : 0,
            'is_verified' => true,
        ]);

        return back()->with('success', 'Publication réussie.');
    }

    public function destroy($id)
    {
        // 1. On récupère la publication ou on renvoie une erreur 404 si elle n'existe pas
        $publication = Publication::findOrFail($id);

        // 2. Sécurité : On vérifie que la publication appartient bien à l'utilisateur connecté
        if ($publication->user_id !== Auth::id()) {
            abort(403, 'Action non autorisée');
        }

        // 3. Suppression des fichiers physiques sur le disque (PDF et Image de couverture)
        if ($publication->file_path) {
            Storage::disk('public')->delete($publication->file_path);
        }

        if ($publication->cover_image) {
            Storage::disk('public')->delete($publication->cover_image);
        }

        // 4. Suppression de la ligne dans la base de données
        $publication->delete();

        // 5. Redirection avec un message de succès
        return back()->with('success', 'La publication a été supprimée avec succès.');
    }
}
