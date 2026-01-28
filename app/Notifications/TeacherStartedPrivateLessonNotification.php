<?php

namespace App\Notifications;

use App\Models\PrivateLessonEnrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeacherStartedPrivateLessonNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $enrollment;

    /**
     * Create a new notification instance.
     */
    public function __construct(PrivateLessonEnrollment $enrollment)
    {
        $this->enrollment = $enrollment;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $lesson = $this->enrollment->privateLesson;
        $teacher = $lesson->teacher;

        return (new MailMessage)
            ->subject("🎥 Le cours \"{$lesson->titre}\" commence maintenant !")
            ->greeting("Bonjour {$notifiable->name} !")
            ->line("Le professeur **{$teacher->name}** a commencé le cours **{$lesson->titre}**.")
            ->line("⏰ Durée: {$lesson->duree_minutes} minutes")
            ->action('Rejoindre la salle', route('private-lessons.room', $this->enrollment->id))
            ->line('Cliquez rapidement pour ne pas manquer le début du cours !');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $lesson = $this->enrollment->privateLesson;
        $teacher = $lesson->teacher;

        return [
            'title' => '🎥 Cours en direct',
            'message' => "Le prof {$teacher->name} a commencé le cours \"{$lesson->titre}\"",
            'enrollment_id' => $this->enrollment->id,
            'lesson_id' => $lesson->id,
            'action_url' => route('private-lessons.room', $this->enrollment->id),
        ];
    }
}
