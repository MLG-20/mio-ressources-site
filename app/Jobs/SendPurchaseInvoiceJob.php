<?php

namespace App\Jobs;

use App\Models\Purchase;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class SendPurchaseInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private readonly Purchase $purchase,
        private readonly ?int $userId,
        private readonly ?string $guestEmail,
        private readonly string $itemType,
        private readonly int $itemId,
        private readonly string $paymentRef,
    ) {}

    public function handle(): void
    {
        $this->purchase->loadMissing(['user', 'ressource.user', 'publication.user']);

        $item = $this->purchase->ressource ?? $this->purchase->publication;
        if (! $item) {
            return;
        }

        $user = $this->userId ? User::find($this->userId) : null;
        $destinataire = $user ? $user->email : $this->guestEmail;

        if (! $destinataire) {
            return;
        }

        $downloadLink = null;
        if (! $user && $this->guestEmail) {
            $downloadLink = URL::temporarySignedRoute(
                'guest.download',
                now()->addHours(24),
                ['token' => $this->paymentRef, 'type' => $this->itemType, 'id' => $this->itemId]
            );
        }

        try {
            $pdf = Pdf::loadView('invoices.template', ['purchase' => $this->purchase]);
            $pdfContent = $pdf->output();

            Mail::send([], [], function ($message) use ($destinataire, $pdfContent, $item, $downloadLink) {
                $message->to($destinataire)
                    ->subject('✅ Votre commande MIO : ' . $item->titre)
                    ->attachData($pdfContent, 'Facture_MIO.pdf', ['mime' => 'application/pdf'])
                    ->html(
                        $downloadLink
                            ? "<h2>Merci !</h2><p>Votre paiement a été validé.</p><p><a href=\"" . e($downloadLink) . "\">Télécharger votre document</a> (lien valable 24h)</p><p>Voici votre facture en pièce jointe.</p>"
                            : "<h2>Merci !</h2><p>Voici votre document et votre facture.</p>"
                    );

                if (file_exists(storage_path('app/public/' . $item->file_path))) {
                    $message->attach(storage_path('app/public/' . $item->file_path));
                }
            });
        } catch (\Exception $e) {
            Log::error('SendPurchaseInvoiceJob: ' . $e->getMessage());
            throw $e;
        }
    }
}
