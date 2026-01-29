<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use App\Models\Ressource; // AJOUTÉ
use App\Observers\RessourceObserver; // AJOUTÉ
use App\Models\PrivateLesson; // AJOUTÉ
use App\Observers\PrivateLessonObserver; // AJOUTÉ
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\PasswordReset;
use App\Listeners\SendPasswordChangedEmail;
use Illuminate\Auth\Events\Login;               // <--- AJOUTÉ
use App\Listeners\SendAdminLoginAlert;          // <--- AJOUTÉ
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Partager les réglages avec le site public
        if (Schema::hasTable('settings')) {
            View::share('globalSettings', Setting::pluck('value', 'key'));
        }

        // 2. Écouter le changement de mot de passe (Sécurité)
        Event::listen(
            PasswordReset::class,
            SendPasswordChangedEmail::class,
        );

         // 3. SÉCURITÉ : Écouter les connexions Admin (Alerte Intrusion)
        Event::listen(
            Login::class,
            SendAdminLoginAlert::class,
        );

        // 4. SURVEILLER LES NOUVELLES RESSOURCES (Pour l'envoi de mail auto)
        // C'EST CETTE LIGNE QUI DÉBLOQUE TOUT
        Ressource::observe(RessourceObserver::class);

        // 5. SURVEILLER LES NOUVEAUX COURS PARTICULIERS (Notification aux étudiants du même niveau)
        PrivateLesson::observe(PrivateLessonObserver::class);
    }
}
