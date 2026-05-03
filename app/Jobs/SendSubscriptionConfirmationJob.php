<?php

namespace App\Jobs;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendSubscriptionConfirmationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private readonly User $user,
        private readonly Carbon $paidUntil,
    ) {}

    public function handle(): void
    {
        $prenom = explode(' ', $this->user->name)[0];
        $dateExpiration = $this->paidUntil->translatedFormat('d F Y');

        try {
            Mail::send([], [], function ($message) use ($prenom, $dateExpiration) {
                $message->to($this->user->email)
                    ->subject('Merci pour votre abonnement MIO Ressources !')
                    ->html("
                        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 30px; border-radius: 8px; background: #f9fafb;'>
                            <div style='text-align: center; margin-bottom: 24px;'>
                                <h1 style='color: #1e40af; font-size: 24px;'>MIO Ressources</h1>
                            </div>
                            <h2 style='color: #1e293b;'>Merci {$prenom} ! 🎉</h2>
                            <p style='color: #475569; font-size: 16px; line-height: 1.6;'>
                                Nous sommes vraiment touchés par la confiance que vous nous accordez.
                                Votre abonnement étudiant est désormais <strong>actif</strong>.
                            </p>
                            <div style='background: #dbeafe; border-left: 4px solid #1e40af; padding: 16px; border-radius: 4px; margin: 24px 0;'>
                                <p style='margin: 0; color: #1e40af; font-weight: bold;'>
                                    ✅ Abonnement valide jusqu'au : {$dateExpiration}
                                </p>
                            </div>
                            <p style='color: #475569; font-size: 16px; line-height: 1.6;'>
                                Vous avez maintenant accès à l'ensemble de votre espace étudiant : cours, groupes, messagerie, cours particuliers et bien plus encore.
                            </p>
                            <p style='color: #64748b; font-size: 14px; margin-top: 32px;'>
                                L'équipe MIO Ressources 💙
                            </p>
                        </div>
                    ");
            });
        } catch (\Exception $e) {
            Log::error('SendSubscriptionConfirmationJob: ' . $e->getMessage());
            throw $e;
        }
    }
}
