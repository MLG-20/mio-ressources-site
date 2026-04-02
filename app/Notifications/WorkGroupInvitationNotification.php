<?php

namespace App\Notifications;

use App\Models\WorkGroup;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WorkGroupInvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $workGroup;
    public $inviter;

    /**
     * Create a new notification instance.
     */
    public function __construct(WorkGroup $workGroup, User $inviter)
    {
        $this->workGroup = $workGroup;
        $this->inviter = $inviter;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
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
            ->subject("🎉 Invitation à rejoindre le groupe \"{$this->workGroup->name}\"")
            ->greeting("Salut {$notifiable->name} ! 👋")
            ->line("**{$this->inviter->name}** t'a invité(e) à rejoindre le groupe de travail **{$this->workGroup->name}** !")
            ->line("Description : " . ($this->workGroup->description ?: 'Aucune description'))
            ->line("Ce groupe dispose d'une salle Jitsi permanente pour vos réunions collaboratives.")
            ->action('Voir le groupe', route('groups.show', $this->workGroup->id))
            ->line("Tu peux maintenant accéder à la salle du groupe et collaborer avec tes camarades ! 🚀");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'work_group_invitation',
            'work_group_id' => $this->workGroup->id,
            'work_group_name' => $this->workGroup->name,
            'inviter_id' => $this->inviter->id,
            'inviter_name' => $this->inviter->name,
            'message' => "{$this->inviter->name} t'a invité(e) dans le groupe {$this->workGroup->name}",
            'icon' => 'fas fa-users text-indigo-500',
        ];
    }
}
