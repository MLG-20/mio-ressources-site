<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendContactMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private readonly string $nom,
        private readonly string $email,
        private readonly string $messageContent,
    ) {}

    public function handle(): void
    {
        $nom = $this->nom;
        $email = $this->email;
        $messageContent = $this->messageContent;

        try {
            Mail::raw(
                "Message de contact depuis le site MIO\n\nNom : {$nom}\nEmail : {$email}\nMessage :\n{$messageContent}",
                function ($message) use ($nom, $email) {
                    $message->to('mlamine.gueye1@univ-thies.sn')
                        ->subject('Nouveau message de contact MIO')
                        ->replyTo($email, $nom);
                }
            );
        } catch (\Exception $e) {
            Log::error('SendContactMessageJob: ' . $e->getMessage());
            throw $e;
        }
    }
}
