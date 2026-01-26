<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChangedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
    return (new MailMessage)
        ->subject('Sécurité : Votre mot de passe a été modifié')
        ->greeting('Bonjour ' . $notifiable->name . ',')
        ->line('Nous vous confirmons que le mot de passe de votre compte MIO Ressources a été modifié avec succès.')
        ->line('📅 Date de modification : ' . now()->format('d/m/Y à H:i'))
        ->line('✅ Si vous êtes à l\'origine de cette action, vous pouvez ignorer cet email.')
        ->line('⚠️ ATTENTION : Si vous n\'avez pas modifié votre mot de passe, cela signifie que quelqu\'un d\'autre a accès à votre compte.')
        ->action('Sécuriser mon compte immédiatement', url(route('password.request')))
        ->salutation('L\'équipe de sécurité MIO');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
