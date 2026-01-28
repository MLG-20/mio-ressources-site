<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkGroup;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notification;
use App\Notifications\WorkGroupInvitationNotification;

class WorkGroupController extends Controller
{
    /**
     * Afficher tous les groupes de l'utilisateur connecté
     */
    public function index()
    {
        $myGroups = Auth::user()->workGroups()->with('creator', 'members')->get();
        return view('work-groups.index', compact('myGroups'));
    }

    /**
     * Créer un nouveau groupe
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        // Créer le groupe
        $group = WorkGroup::create([
            'name' => $request->name,
            'description' => $request->description,
            'creator_id' => Auth::id(),
        ]);

        // Ajouter le créateur comme membre
        $group->members()->attach(Auth::id());

        return redirect()->route('user.dashboard')->with('success', 'Groupe créé avec succès !')->withFragment('groupes');
    }

    /**
     * Afficher la salle Jitsi d'un groupe (avec vérification d'accès)
     */
    public function show($id)
    {
        $group = WorkGroup::with('members', 'creator')->findOrFail($id);

        // SÉCURITÉ : Vérifier que l'utilisateur est membre du groupe
        if (!$group->hasMember(Auth::id())) {
            abort(403, 'Vous n\'êtes pas membre de ce groupe.');
        }

        return view('work-groups.show', compact('group'));
    }

    /**
     * Inviter un étudiant par email
     */
    public function invite(Request $request, $id)
    {
        $group = WorkGroup::with('members')->findOrFail($id);

        // Vérifier que l'utilisateur est le créateur
        if ($group->creator_id !== Auth::id()) {
            abort(403, 'Seul le créateur peut inviter des membres.');
        }

        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        // Vérifier que c'est un étudiant
        if ($user->user_type !== 'student') {
            return redirect()->back()->with('error', 'Seuls les étudiants peuvent être invités.');
        }

        // Vérifier que c'est le même niveau d'études
        $creator = Auth::user();
        if ($user->student_level !== $creator->student_level) {
            return redirect()->back()->with('error', "Cet étudiant n'est pas du même niveau ({$user->student_level}).");
        }

        // Vérifier si déjà membre
        if ($group->hasMember($user->id)) {
            return redirect()->back()->with('error', 'Cet utilisateur est déjà membre.');
        }

        // Ajouter au groupe
        $group->members()->attach($user->id);

        // Envoyer la notification par email avec Notification::send pour plus de fiabilité
        Notification::send($user, new WorkGroupInvitationNotification($group, Auth::user()));

        return redirect()->route('user.dashboard')->with('success', "Invitation envoyée à {$user->name} ({$user->email}) !")->withFragment('groupes');
    }

    /**
     * Supprimer un membre du groupe (créateur uniquement)
     */
    public function removeMember($id, $userId)
    {
        $group = WorkGroup::with('members')->findOrFail($id);

        // Vérifier que l'utilisateur est le créateur
        if ($group->creator_id !== Auth::id()) {
            abort(403, 'Seul le créateur peut supprimer des membres.');
        }

        // Vérifier que le membre existe
        if (!$group->hasMember($userId)) {
            return redirect()->back()->with('error', 'Ce membre n\'existe pas dans le groupe.');
        }

        // Empêcher de supprimer le créateur
        if ($userId === $group->creator_id) {
            return redirect()->back()->with('error', 'Impossible de supprimer le créateur du groupe.');
        }

        $removedUser = User::findOrFail($userId);
        $group->members()->detach($userId);

        return redirect()->back()->with('success', "{$removedUser->name} a été retiré du groupe.");
    }

    /**
     * Quitter un groupe
     */
    public function leave($id)
    {
        $group = WorkGroup::findOrFail($id);

        // Le créateur ne peut pas quitter son propre groupe
        if ($group->creator_id === Auth::id()) {
            return redirect()->route('user.dashboard')->with('error', 'Le créateur ne peut pas quitter le groupe. Supprimez-le à la place.')->withFragment('groupes');
        }

        $group->members()->detach(Auth::id());

        return redirect()->route('user.dashboard')->with('success', 'Vous avez quitté le groupe.')->withFragment('groupes');
    }

    /**
     * Supprimer un groupe (créateur uniquement)
     */
    public function destroy($id)
    {
        $group = WorkGroup::findOrFail($id);

        if ($group->creator_id !== Auth::id()) {
            abort(403, 'Seul le créateur peut supprimer le groupe.');
        }

        $group->delete();

        return redirect()->route('user.dashboard')->with('success', 'Groupe supprimé avec succès.')->withFragment('groupes');
    }
}
