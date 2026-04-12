<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Notifications\TeacherJoinedMeetingAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Notification;

class ScheduledMeetingController extends Controller
{
    /**
     * Display a listing of the resource (Prof dashboard)
     */
    public function index()
    {
        // Redirection vers l'espace enseignant
        return redirect()->route('teacher.dashboard');
    }

    /**
     * Show the form for creating a new resource
     */
    public function create()
    {
        // Redirection vers l'espace enseignant
        return redirect()->route('teacher.dashboard');
    }

    /**
     * Store a newly created resource in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'scheduled_at' => 'required|date|after:now',
        ]);

        $meeting = auth()->user()->meetings()->create([
            'title' => $validated['title'],
            'scheduled_at' => $validated['scheduled_at'],
            'room_name' => 'MIO-COURSE-' . Str::random(8),
            'status' => 'scheduled',
        ]);

        return redirect()->route('teacher.dashboard')->with('success', 'Cours créé avec succès ! Cliquez sur "Cours Vidéo" pour le voir.');
    }

    /**
     * Display the specified resource (Join meeting)
     */
    public function show(Meeting $scheduled_meeting)
    {
        // Eager load les étudiants inscrits pour éviter N+1
        $scheduled_meeting->load('enrolledStudents');
        
        // Vérifier que l'utilisateur est le prof OU est inscrit au cours
        if ($scheduled_meeting->user_id !== auth()->id() && !auth()->user()->enrolledMeetings->contains($scheduled_meeting->id)) {
            abort(403);
        }

        $isModerator = $scheduled_meeting->user_id === auth()->id();

        // Si le cours n'est pas encore actif, le rendre actif et notifier les étudiants
        if ($scheduled_meeting->status === 'scheduled' && $isModerator) {
            $scheduled_meeting->update([
                'status' => 'active',
                'started_at' => now(),
            ]);

            // 🚀 ALERTE LES ÉTUDIANTS QUE LE PROF A REJOINT LE COURS
            $enrolledStudents = $scheduled_meeting->enrolledStudents;
            if ($enrolledStudents->count() > 0) {
                Notification::send($enrolledStudents, new TeacherJoinedMeetingAlert($scheduled_meeting, auth()->user()));
            }
        }

        $domain = config('services.jitsi.domain', env('JITSI_DOMAIN', 'meet.jit.si'));

        return view('scheduled-meetings.show', [
            'meeting' => $scheduled_meeting,
            'room' => $scheduled_meeting->room_name,
            'domain' => $domain,
            'user' => auth()->user(),
            'isModerator' => $isModerator,
        ]);
    }

    /**
     * Close meeting (Prof only, manual close)
     */
    public function close(Meeting $scheduled_meeting)
    {
        if ($scheduled_meeting->user_id !== auth()->id()) {
            abort(403);
        }

        $scheduled_meeting->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        return redirect()->route('scheduled-meetings.index')->with('success', 'Cours fermé');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meeting $scheduled_meeting)
    {
        // Sécurité : seul le créateur peut supprimer
        if ($scheduled_meeting->user_id !== auth()->id()) {
            abort(403);
        }

        $scheduled_meeting->delete();

        return redirect()->back()->with('success', 'Réunion supprimée avec succès.');
    }
}
