<?php

namespace App\Observers;

use App\Models\PrivateLesson;
use App\Models\User;
use App\Notifications\NewPrivateLessonNotification;
use Illuminate\Support\Facades\Notification;

class PrivateLessonObserver
{
    /**
     * Se déclenche juste après la création d'un cours particulier
     */
    public function created(PrivateLesson $privateLesson): void
    {
        // Charger la relation matiere pour accéder au niveau
        $privateLesson->load('matiere');

        // Récupérer les étudiants du niveau sélectionné pour ce cours
        $students = User::where('user_type', 'student')
            ->where('student_level', $privateLesson->student_level)
            ->get();

        // Envoyer la notification à chaque étudiant
        Notification::send($students, new NewPrivateLessonNotification($privateLesson));
    }

    /**
     * Optionnel : Envoyer une alerte si le cours est mis à jour/annulé
     */
    public function updated(PrivateLesson $privateLesson): void
    {
        // Exemple : Si le statut passe à 'annulé', on peut notifier les inscrits
        if ($privateLesson->isDirty('statut') && $privateLesson->statut === 'annulé') {
            $enrollments = $privateLesson->enrollments()
                ->where('payment_status', 'paid')
                ->get();

            foreach ($enrollments as $enrollment) {
                $student = $enrollment->student;
                // Optionnel : créer une notification d'annulation
                // $student->notify(new PrivateLessonCancelledNotification($privateLesson));
            }
        }
    }
}
