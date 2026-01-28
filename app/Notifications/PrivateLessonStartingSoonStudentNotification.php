<?php

namespace App\Notifications;

use App\Models\PrivateLesson;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PrivateLessonStartingSoonStudentNotification extends Notification implements ShouldQueue
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
        return (new MailMessage)
            ->subject('⏰ URGENT : Votre cours commence dans 15 minutes !')
            ->greeting('🚨 Alerte Étudiant!')
            ->line('Votre cours particulier commence **DANS 15 MINUTES** !!')
            ->line('**' . $this->lesson->titre . '**')
            ->line('👨‍🏫 **Professeur :** ' . $this->lesson->teacher->name)
            ->line('🕐 **Heure de début :** ' . $this->lesson->start_date->format('H:i'))
            ->line('⏱️ **Durée :** ' . $this->lesson->duree_minutes . ' minutes')
            ->line('✅ Assurez-vous que :')
            ->line('- Votre connexion Internet est stable')
            ->line('- Votre caméra et micro fonctionnent')
            ->line('- Vous avez un espace calme et bien éclairé')
            ->line('- Vous avez fermé les applications qui consomment de la bande passante')
            ->action('Accéder à mon espace', url(route('user.dashboard', [], false) . '#courses'))
            ->line('À bientôt ! 🎓');
    }

    public function toArray($notifiable)
    {
        return [
            'lesson_id' => $this->lesson->id,
            'lesson_title' => $this->lesson->titre,
            'teacher_name' => $this->lesson->teacher->name,
            'start_date' => $this->lesson->start_date,
        ];
    }
}
