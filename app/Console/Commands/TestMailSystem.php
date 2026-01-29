<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Notifications\AdminLoginAlert;

class TestMailSystem extends Command
{
    protected $signature = 'mail:test {email?}';
    protected $description = 'Tester le système d\'envoi de mails';

    public function handle()
    {
        $email = $this->argument('email') ?? env('MAIL_FROM_ADDRESS');

        $this->info("🔍 Test du système mail...");
        $this->info("📧 Email de destination: {$email}");
        
        // Test 1: Configuration
        $this->info("\n1️⃣ Vérification de la configuration:");
        $this->line("   MAIL_MAILER: " . config('mail.default'));
        $this->line("   MAIL_HOST: " . config('mail.mailers.smtp.host'));
        $this->line("   MAIL_PORT: " . config('mail.mailers.smtp.port'));
        $this->line("   MAIL_FROM: " . config('mail.from.address'));
        $this->line("   QUEUE_CONNECTION: " . config('queue.default'));

        // Test 2: Envoi d'un mail simple
        $this->info("\n2️⃣ Test d'envoi simple:");
        try {
            Mail::raw('🎉 Test MIO Ressources: Le système mail fonctionne!', function ($message) use ($email) {
                $message->to($email)
                    ->subject('✅ Test système mail - ' . now()->format('H:i:s'));
            });
            $this->info("   ✅ Mail simple envoyé avec succès!");
        } catch (\Exception $e) {
            $this->error("   ❌ Erreur: " . $e->getMessage());
            return 1;
        }

        // Test 3: Notification avec queue
        $this->info("\n3️⃣ Test de notification en queue:");
        try {
            $adminUser = User::find(1);
            if ($adminUser) {
                $adminUser->notify(new AdminLoginAlert($adminUser, '127.0.0.1'));
                $this->info("   ✅ Notification Admin envoyée en queue!");
                $this->warn("   ⏳ Vérifiez que php artisan queue:work tourne");
            } else {
                $this->warn("   ⚠️  User ID 1 introuvable");
            }
        } catch (\Exception $e) {
            $this->error("   ❌ Erreur: " . $e->getMessage());
        }

        // Test 4: Vérification de la queue
        $this->info("\n4️⃣ Vérification de la queue:");
        $this->line("   Commande pour voir la queue: php artisan queue:work");
        $this->line("   Commande pour vider la queue: php artisan queue:flush");

        $this->info("\n🎯 Test terminé! Vérifiez votre boîte mail: {$email}");
        $this->warn("⚠️  Si vous ne recevez rien, vérifiez:");
        $this->line("   - Les credentials SMTP dans .env");
        $this->line("   - Le mot de passe d'application Gmail (pas le mot de passe normal)");
        $this->line("   - Que queue:work tourne en prod");
        $this->line("   - Les logs: storage/logs/laravel.log");

        return 0;
    }
}
