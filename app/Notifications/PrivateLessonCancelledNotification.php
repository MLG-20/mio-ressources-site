<?php

namespace App\Notifications;

use App\Models\PrivateLesson;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PrivateLessonCancelledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $lesson;
    protected $teacherName;

    public function __construct(PrivateLesson $lesson, $teacherName)
    {
        $this->lesson = $lesson;
        $this->teacherName = $teacherName;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('⚠️ Cours Particulier Annulé')
            ->greeting('Bonjour ' . $notifiable->name . ' 👋')
            ->line('Nous sommes désolés de vous informer que le cours suivant a été annulé par le professeur :')
            ->line('**' . $this->lesson->titre . '**')
            ->line('👨‍🏫 **Professeur :** ' . $this->teacherName)
            ->line('📅 **Date prévue :** ' . ($this->lesson->start_date ? $this->lesson->start_date->format('d/m/Y à H:i') : 'Non définie'))
            ->line('Si vous avez payé pour ce cours, un remboursement sera effectué dans les plus brefs délais.')
            ->action('Parcourir d\'autres cours', route('private-lessons.browse'))
            ->line('Merci de votre compréhension. 🙏');
    }

    public function toArray($notifiable)
    {
        return [
            'lesson_id' => $this->lesson->id,
            'lesson_title' => $this->lesson->titre,
            'teacher_name' => $this->teacherName,
        ];
    }
}
