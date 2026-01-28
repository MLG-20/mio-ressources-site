<?php

namespace App\Notifications;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeacherJoinedMeetingAlert extends Notification implements ShouldQueue
{
    use Queueable;

    public $meeting;
    public $teacher;

    /**
     * Create a new notification instance.
     */
    public function __construct(Meeting $meeting, User $teacher)
    {
        $this->meeting = $meeting;
        $this->teacher = $teacher;
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
        return (new MailMessage)
            ->subject("🔴 ALERTE: Votre prof est en ligne pour le cours \"{$this->meeting->title}\"")
            ->greeting("Coucou {$notifiable->name} ! 👋")
            ->line("**{$this->teacher->name}** vient de lancer le cours **{$this->meeting->title}** !")
            ->line("Le cours est maintenant **ACTIF** sur la plateforme Jitsi Meet.")
            ->action('Rejoindre le cours maintenant', route('scheduled-meetings.show', $this->meeting->id))
            ->line("Dépêche-toi, le prof t'attend ! ⏰");
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'teacher_joined_meeting',
            'meeting_id' => $this->meeting->id,
            'meeting_title' => $this->meeting->title,
            'teacher_id' => $this->teacher->id,
            'teacher_name' => $this->teacher->name,
            'message' => "{$this->teacher->name} a lancé le cours {$this->meeting->title}",
            'icon' => 'fas fa-video text-red-500',
        ];
    }
}
