<?php

namespace App\Jobs;

use App\Models\Purchase;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPurchaseConfirmationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private readonly Purchase $purchase,
        private readonly string $emailDest,
        private readonly string $nomDest,
    ) {}

    public function handle(): void
    {
        $this->purchase->loadMissing(['ressource.user', 'publication.user', 'user']);

        $item = $this->purchase->ressource ?? $this->purchase->publication;
        if (! $item) {
            return;
        }

        $filePath = storage_path('app/public/' . $item->file_path);
        $nomDest = $this->nomDest;

        try {
            $pdf = Pdf::loadView('invoices.template', ['purchase' => $this->purchase]);

            Mail::send([], [], function ($message) use ($item, $pdf, $filePath, $nomDest) {
                $email = $message
                    ->from(config('mail.from.address', 'noreply@mio.sn'), config('mail.from.name', 'MIO Ressources'))
                    ->to($this->emailDest)
                    ->subject('✅ Votre commande est arrivée : ' . $item->titre)
                    ->html("<h2>Merci {$nomDest} !</h2>
                            <p>Voici votre commande MIO Ressources.</p>
                            <p>Vous trouverez ci-joint :</p>
                            <ul>
                                <li>Votre Facture (Preuve d'achat)</li>
                                <li><strong>Le Document acheté</strong> (PDF)</li>
                            </ul>
                            <p>Bonne lecture !</p>");

                $email->attachData($pdf->output(), 'Facture_MIO.pdf', ['mime' => 'application/pdf']);

                if ($item->type !== 'Vidéo' && file_exists($filePath)) {
                    $email->attach($filePath);
                }
            });
        } catch (\Exception $e) {
            Log::error('SendPurchaseConfirmationJob: ' . $e->getMessage());
            throw $e;
        }
    }
}
