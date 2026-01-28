<?php

namespace App\Notifications;

use App\Models\Purchase;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PurchaseInvoiceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $purchase;

    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $itemName = $this->purchase->ressource
            ? $this->purchase->ressource->titre
            : $this->purchase->publication->titre;

        $itemType = $this->purchase->ressource ? 'ressource' : 'publication';
        $typeLabel = $this->purchase->ressource ? 'Document PDF' : 'Livre/Publication';

        return (new MailMessage)
            ->subject('📄 Votre facture MIO Ressources - Achat confirmé')
            ->greeting('Bonjour ' . $notifiable->name . ' 👋')
            ->line('Merci pour votre achat sur MIO Ressources!')
            ->line('**Détails de votre achat :**')
            ->line('📚 **Article :** ' . $itemName)
            ->line('🏷️ **Type :** ' . $typeLabel)
            ->line('💰 **Montant :** ' . number_format($this->purchase->amount, 0, ',', ' ') . ' F')
            ->line('🆔 **Référence :** MIO-' . $this->purchase->id)
            ->line('📅 **Date :** ' . $this->purchase->created_at->format('d/m/Y à H:i'))
            ->line('---')
            ->line('Votre accès est immédiat. Vous pouvez dès maintenant télécharger votre document depuis votre espace.')
            ->action('Voir ma facture', route('invoice.download', $this->purchase->id))
            ->line('---')
            ->line('**Informations utiles :**')
            ->line('- Gardez cette facture à titre de preuve d\'achat')
            ->line('- Vous pouvez la re-télécharger à tout moment depuis votre historique')
            ->line('- En cas de problème, contactez notre support')
            ->salutation('L\'équipe MIO Ressources 🎓');
    }

    public function toArray($notifiable)
    {
        return [
            'purchase_id' => $this->purchase->id,
            'amount' => $this->purchase->amount,
            'item_type' => $this->purchase->item_type,
        ];
    }
}
