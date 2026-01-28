<?php

namespace App\Notifications;

use App\Models\Meeting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ScheduledMeetingReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public $meeting;
    public $minutesBefore;

    /**
     * Create a new notification instance.
     */
    public function __construct(Meeting $meeting, int $minutesBefore = 60)
    {
        $this->meeting = $meeting;
        $this->minutesBefore = $minutesBefore;
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
        $message = match($this->minutesBefore) {
            1440 => "Demain",
            60 => "dans 1 heure",
            0 => "commence maintenant",
            default => "dans {$this->minutesBefore} minutes"
        };

        return (new MailMessage)
            ->subject("Rappel: Cours \"{{ $this->meeting->title }}\" $message")
            ->greeting("Bonjour {{ $notifiable->name }} !")
            ->line("Le cours **{$this->meeting->title}** démarre $message.")
            ->line("Professeur: **{$this->meeting->user->name}**")
            ->line("Heure: {$this->meeting->scheduled_at->format('d/m/Y H:i')}")
            ->action('Rejoindre la salle', route('scheduled-meetings.show', $this->meeting))
            ->line('À bientôt dans MIO Ressources!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'meeting_id' => $this->meeting->id,
            'title' => $this->meeting->title,
            'professor' => $this->meeting->user->name,
            'scheduled_at' => $this->meeting->scheduled_at,
        ];
    }
}
