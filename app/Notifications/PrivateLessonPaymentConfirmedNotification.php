<?php

namespace App\Notifications;

use App\Models\PrivateLessonEnrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PrivateLessonPaymentConfirmedNotification extends Notification
{
    use Queueable;

    protected PrivateLessonEnrollment $enrollment;

    public function __construct(PrivateLessonEnrollment $enrollment)
    {
        $this->enrollment = $enrollment;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $lesson = $this->enrollment->privateLesson;
        $teacher = $lesson->teacher;

        return (new MailMessage)
            ->subject("Confirmation de paiement - Cours particulier {$lesson->titre}")
            ->greeting("Bonjour {$notifiable->prenom},")
            ->line("Votre paiement a été confirmé avec succès! 🎉")
            ->line("")
            ->line("**Détails du cours:**")
            ->line("• Titre: {$lesson->titre}")
            ->line("• Professeur: {$teacher->prenom} {$teacher->name}")
            ->line("• Date: " . $lesson->start_date->format('d/m/Y à H:i'))
            ->line("• Durée: {$lesson->duree_minutes} minutes")
            ->line("• Montant payé: " . number_format($this->enrollment->amount_paid / 100, 2) . " CFA")
            ->line("")
            ->line("**Numéro de référence:** {$this->enrollment->payment_reference}")
            ->line("")
            ->action('Accéder au cours', route('private-lessons.room', $this->enrollment->id))
            ->line("")
            ->line("Vous recevrez un rappel email 15 minutes avant le démarrage du cours.")
            ->line("")
            ->salutation("Cordialement, l'équipe MIO Ressources");
    }
}
