<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class AdminLoginAlert extends Notification implements ShouldQueue
{
    use Queueable;

    public $user; // L'admin qui vient de se connecter
    public $ip;   // Son adresse IP
    public $time; // L'heure

    public function __construct(User $user, $ip)
    {
        $this->user = $user;
        $this->ip = $ip;
        $this->time = now()->format('d/m/Y à H:i:s');
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚠️ ALERTE SÉCURITÉ : Connexion Admin détectée')
            ->greeting('Bonjour Super Admin,')
            ->line('Une nouvelle connexion vient d\'être détectée sur le Dashboard Admin.')
            ->line('👤 Utilisateur : **' . $this->user->name . '** (' . $this->user->email . ')')
            ->line('🌍 Adresse IP : ' . $this->ip)
            ->line('🕒 Date & Heure : ' . $this->time)
            ->line('Si vous ne reconnaissez pas cette connexion, veuillez changer les accès immédiatement.')
            ->action('Gérer les utilisateurs', url('/admin/users'))
            ->salutation('Système de Sécurité MIO');
    }
}