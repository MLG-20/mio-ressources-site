<?php

namespace App\Http\Controllers;

use App\Models\ForumCategory;
use App\Models\ForumSujet;
use App\Models\ForumMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    // 1. Liste des catégories
    public function index() {
        $categories = ForumCategory::withCount('sujets')->orderBy('ordre')->get();
        return view('forum.index', compact('categories'));
    }

    // 2. Liste des sujets dans une catégorie
    public function showCategory($id) {
        $category = ForumCategory::findOrFail($id);
        $sujets = ForumSujet::where('forum_category_id', $id)
            ->with(['user'])
            ->withCount('messages')
            ->orderBy('est_epingle', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('forum.category', compact('category', 'sujets'));
    }

    // 3. Afficher un sujet et ses messages
    public function showSujet($id) {
        $sujet = ForumSujet::with(['user', 'category', 'messages.user'])->findOrFail($id);
        
        // Incrémenter le nombre de vues
        $sujet->increment('nombre_vues');

        return view('forum.show', compact('sujet'));
    }

    // 4. Poster une réponse
    public function storeMessage(Request $request, $id) {
        $request->validate(['contenu' => 'required|min:2']);

        ForumMessage::create([
            'forum_sujet_id' => $id,
            'user_id' => Auth::id(),
            'contenu' => $request->contenu
        ]);

        return back()->with('success', 'Votre message a été posté !');
    }

    public function storeSujet(Request $request)
{
    $request->validate([
        'titre' => 'required|min:5|max:255',
        'forum_category_id' => 'required|exists:forum_categories,id'
    ]);

    $sujet = ForumSujet::create([
        'titre' => $request->titre,
        'forum_category_id' => $request->forum_category_id,
        'user_id' => Auth::id(),
    ]);

    return redirect()->route('forum.sujet', $sujet->id)->with('success', 'Discussion lancée !');
}
}