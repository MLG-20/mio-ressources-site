<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PurchaseConfirmationNotification extends Notification
{
    use Queueable;

    public $ressource;

    public function __construct($ressource)
    {
        $this->ressource = $ressource;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('✅ Merci pour votre contribution - ' . $this->ressource->titre)
            ->greeting('Merci beaucoup ' . $notifiable->name . ' !')
            ->line('Votre paiement pour le document "' . $this->ressource->titre . '" a été validé avec succès.')
            ->line('En achetant ce support, vous ne faites pas que réviser : vous contribuez directement à l\'évolution de MIO Ressources et à l\'entraide entre les étudiants de l\'Université Iba Der Thiam.')
            ->action('Ouvrir mon document', url('/mon-espace'))
            ->line('Le document est désormais débloqué à vie dans votre espace personnel, section "Historique & Achats".')
            ->line('Bonnes révisions, nous sommes fiers de vous accompagner dans votre réussite !')
            ->salutation('L\'équipe MIO Ressources');
    }
}