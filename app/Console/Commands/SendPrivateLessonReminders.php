<?php

namespace App\Console\Commands;

use App\Models\PrivateLesson;
use App\Notifications\PrivateLessonStartingSoonNotification;
use App\Notifications\PrivateLessonStartingSoonStudentNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendPrivateLessonReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-private-lesson-reminders';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Envoyer un rappel aux professeurs et étudiants 15 minutes avant leurs cours particuliers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Trouver tous les cours qui commencent dans les 15 minutes
        // On ajoute un peu de marge (14-16 minutes) pour éviter les doublons
        $now = now();
        $fifteenMinutesFromNow = $now->copy()->addMinutes(15);
        $thirteenMinutesFromNow = $now->copy()->addMinutes(13);

        $lessons = PrivateLesson::whereBetween('start_date', [$thirteenMinutesFromNow, $fifteenMinutesFromNow])
            ->with(['teacher', 'enrollments'])
            ->get();

        foreach ($lessons as $lesson) {
            // Vérifier qu'on n'a pas déjà envoyé ce rappel
            // Utiliser une colonne pour tracker ou une clé unique
            $reminderSent = DB::table('private_lesson_reminders_sent')
                ->where('lesson_id', $lesson->id)
                ->where('reminder_type', 'fifteen_minutes')
                ->exists();

            if (!$reminderSent) {
                // Envoyer la notification au professeur
                $lesson->teacher->notify(new PrivateLessonStartingSoonNotification($lesson));

                // Envoyer la notification à tous les étudiants inscrits (payés)
                $enrolledStudents = $lesson->enrollments()
                    ->where('payment_status', 'paid')
                    ->with('student')
                    ->get();

                foreach ($enrolledStudents as $enrollment) {
                    $enrollment->student->notify(new PrivateLessonStartingSoonStudentNotification($lesson));
                }

                // Marquer comme envoyé
                DB::table('private_lesson_reminders_sent')->insert([
                    'lesson_id' => $lesson->id,
                    'reminder_type' => 'fifteen_minutes',
                    'sent_at' => now(),
                    'created_at' => now(),
                ]);

                $studentCount = $enrolledStudents->count();
                $this->info("Rappel envoyé pour le cours: {$lesson->titre} (1 prof + {$studentCount} étudiant(s))");
            }
        }

        $this->info('Commande terminée avec succès.');
    }
}
