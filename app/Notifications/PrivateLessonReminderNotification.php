<?php

namespace App\Notifications;

use App\Models\PrivateLesson;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PrivateLessonReminderNotification extends Notification implements ShouldQueue
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
            ->subject('📅 Rappel : Cours Particulier Programmé')
            ->greeting('Bonjour ' . $notifiable->name . ' 👋')
            ->line('Vous avez un cours particulier programmé :')
            ->line('**' . $this->lesson->titre . '**')
            ->line('📅 **Date :** ' . $this->lesson->start_date->format('d/m/Y'))
            ->line('🕐 **Heure :** ' . $this->lesson->start_date->format('H:i'))
            ->line('⏱️ **Durée :** ' . $this->lesson->duree_minutes . ' minutes')
            ->line('👥 **Étudiants inscrits :** ' . $studentsCount)
            ->line('Préparez votre matériel et soyez prêt à démarrer à l\'heure.')
            ->action('Voir mes cours', route('teacher.private-lessons.index'))
            ->line('Merci d\'utiliser MIO Ressources ! 🎓');
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
