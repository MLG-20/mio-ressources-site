<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;

class StudentCourseController extends Controller
{
    /**
     * Afficher tous les cours disponibles pour inscription
     */
    public function index()
    {
        $user = auth()->user();

        // Cours disponibles (programmés ou actifs, pas fermés)
        $availableCourses = Meeting::whereIn('status', ['scheduled', 'active'])
            ->with('user')
            ->orderBy('scheduled_at', 'asc')
            ->get();

        // Cours auxquels l'étudiant est inscrit
        $myCourses = $user->enrolledMeetings()
            ->whereIn('status', ['scheduled', 'active'])
            ->with('user')
            ->orderBy('scheduled_at', 'asc')
            ->get();

        return view('student.courses', compact('availableCourses', 'myCourses'));
    }

    /**
     * S'inscrire à un cours
     */
    public function enroll(Meeting $meeting)
    {
        $user = auth()->user();

        // Vérifier que le cours n'est pas fermé
        if ($meeting->status === 'closed') {
            return back()->with('error', 'Ce cours est terminé.');
        }

        // Vérifier que l'utilisateur n'est pas déjà inscrit
        if ($user->enrolledMeetings()->where('meeting_id', $meeting->id)->exists()) {
            return back()->with('info', 'Vous êtes déjà inscrit à ce cours.');
        }

        // Inscription
        $user->enrolledMeetings()->attach($meeting->id);

        return back()->with('success', 'Inscription réussie ! Vous pouvez rejoindre ce cours.');
    }

    /**
     * Se désinscrire d'un cours
     */
    public function unenroll(Meeting $meeting)
    {
        $user = auth()->user();

        // Vérifier que l'utilisateur est bien inscrit
        if (!$user->enrolledMeetings()->where('meeting_id', $meeting->id)->exists()) {
            return back()->with('error', 'Vous n\'êtes pas inscrit à ce cours.');
        }

        // Désinscription
        $user->enrolledMeetings()->detach($meeting->id);

        return back()->with('success', 'Vous vous êtes désinscrit de ce cours.');
    }
}
