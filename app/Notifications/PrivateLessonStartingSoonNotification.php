<?php

namespace App\Notifications;

use App\Models\PrivateLesson;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PrivateLessonStartingSoonNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $lesson;

    public function __construct(PrivateLesson $lesson)
    {
        $this->lesson = $lesson;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $studentsCount = $this->lesson->enrollments()->where('payment_status', 'paid')->count();

        return (new MailMessage)
            ->subject('⏰ URGENT : Votre cours commence dans 15 minutes !')
            ->greeting('🚨 Alerte Professeur!')
            ->line('Votre cours particulier commence **DANS 15 MINUTES** !!')
            ->line('**' . $this->lesson->titre . '**')
            ->line('🕐 **Heure de début :** ' . $this->lesson->start_date->format('H:i'))
            ->line('👥 **Étudiants attendus :** ' . $studentsCount)
            ->line('✅ Assurez-vous que :')
            ->line('- Votre connexion Internet est stable')
            ->line('- Votre caméra et micro fonctionnent')
            ->line('- Vous avez accès à votre matériel pédagogique')
            ->line('- La salle Jitsi est prête')
            ->action('Accéder à la salle de cours', route('private-lessons.room', $this->lesson->id))
            ->line('À bientôt ! 🎓');
    }

    public function toArray($notifiable)
    {
        return [
            'lesson_id' => $this->lesson->id,
            'lesson_title' => $this->lesson->titre,
            'start_date' => $this->lesson->start_date,
        ];
    }
}
