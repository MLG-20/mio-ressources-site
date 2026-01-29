<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ressource; // Chemin bien présent

class NewResourceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    // 1. On déclare la propriété pour qu'elle soit accessible dans toMail
    public $ressource;

    /**
     * 2. On récupère la ressource au moment du déclenchement
     */
    public function __construct(Ressource $ressource)
    {
        $this->ressource = $ressource;
    }

    /**
     * On définit que la notification part par Email
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * 3. Construction du message Email
     */
    public function toMail(object $notifiable): MailMessage
{
    // 1. On prépare les labels personnalisés
    $typeLabel = match($this->ressource->type) {
        'Vidéo' => '🎥 Tutoriel Vidéo',
        'TD' => '📝 Travaux Dirigés (TD)',
        'Cours' => '📚 Support de Cours',
        default => '📁 Nouvelle Ressource',
    };

    $prixLabel = $this->ressource->is_premium 
        ? '💎 Contenu Premium (' . $this->ressource->price . ' CFA)' 
        : '✅ Accès Gratuit';

    // 2. On construit le mail
    return (new MailMessage)
        ->subject($typeLabel . ' disponible - ' . $this->ressource->matiere->semestre->niveau)
        ->greeting('Bonjour ' . $notifiable->name . ' !')
        ->line('Une nouvelle ressource vient d\'être publiée pour votre niveau d\'étude.')
        ->line('--------------------------------------------------')
        ->line('📖 Matière : ' . $this->ressource->matiere->nom)
        ->line('📝 Titre : ' . $this->ressource->titre)
        ->line('🏷️ Type : ' . $typeLabel)
        ->line('💰 Accès : ' . $prixLabel)
        ->line('--------------------------------------------------')
        ->action('Accéder au document', url('/matiere/' . $this->ressource->matiere_id))
        ->line('Travailler régulièrement est la clé de votre réussite en ' . $this->ressource->matiere->semestre->niveau . '.')
        ->salutation('L\'équipe MIO Ressources');
}

    public function toArray(object $notifiable): array
    {
        return [
            'ressource_id' => $this->ressource->id,
            'titre' => $this->ressource->titre,
        ];
    }
}