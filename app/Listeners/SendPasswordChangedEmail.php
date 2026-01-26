<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\PasswordChangedNotification; // <--- C'est lui qui manquait !


class SendPasswordChangedEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PasswordReset $event): void
    {
        // On dit explicitement à l'éditeur : "Ceci est un User"
        /** @var User $user */
        $user = $event->user;

        // Maintenant, l'autocomplétion fonctionne et le jaune disparaît
        $user->notify(new PasswordChangedNotification());
    }
    
}