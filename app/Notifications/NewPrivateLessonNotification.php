<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PrivateLesson;

class NewPrivateLessonNotification extends Notification
{

    public $privateLesson;

    /**
     * Construire la notification avec le cours particulier
     */
    public function __construct(PrivateLesson $privateLesson)
    {
        $this->privateLesson = $privateLesson;
    }

    /**
     * Définir les canaux de livraison (email)
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Construction du message Email
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Charger les relations
        $this->privateLesson->load(['teacher', 'matiere']);

        // Formater la date
        $dateFormatted = $this->privateLesson->start_date->format('d/m/Y à H:i');

        // Formater le type de cours
        $typeLabel = $this->privateLesson->type === 'payant' ? '💎 Payant' : '✅ Gratuit';

        // Prix
        $prixLabel = $this->privateLesson->type === 'payant'
            ? $this->privateLesson->prix . ' CFA'
            : 'Accès Gratuit';

        return (new MailMessage)
            ->subject('🎓 Nouveau cours particulier disponible - ' . $this->privateLesson->matiere->nom)
            ->greeting('Bonjour ' . $notifiable->name . ' !')
            ->line('Un professeur vient de publier un nouveau cours particulier qui concerne votre niveau d\'étude!')
            ->line('—————————————————————————————————————')
            ->line('📚 **Matière** : ' . $this->privateLesson->matiere->nom)
            ->line('👨‍🏫 **Professeur** : ' . $this->privateLesson->teacher->name)
            ->line('📖 **Titre** : ' . $this->privateLesson->titre)
            ->line('⏱️ **Durée** : ' . $this->privateLesson->duree_minutes . ' minutes')
            ->line('📅 **Date & Heure** : ' . $dateFormatted)
            ->line('💰 **Tarif** : ' . $prixLabel . ' (' . $typeLabel . ')')
            ->line('👥 **Places disponibles** : ' . $this->privateLesson->places_max)
            ->line('—————————————————————————————————————')
            ->line('📝 **Description** :')
            ->line($this->privateLesson->description)
            ->line('—————————————————————————————————————')
            ->action('Voir le cours et réserver', route('private-lessons.show', $this->privateLesson->id))
            ->line('Accédez à votre espace pour consulter tous les cours particuliers disponibles.')
            ->salutation('L\'équipe MIO Ressources');
    }
}
