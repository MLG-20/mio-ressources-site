<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\User;
use App\Notifications\AdminLoginAlert;
use Illuminate\Support\Facades\Request;

class SendAdminLoginAlert
{
    public function handle(Login $event): void
    {
        /** @var User $user */
        $user = $event->user;

        // ON VÉRIFIE SI C'EST UN ADMIN OU UN PROF (Accès Dashboard)
        if ($user->role === 'admin' || $user->user_type === 'teacher') {
            
            // On récupère le Super Admin (Toi, ID 1)
            $superAdmin = User::find(1);

            // On évite de t'envoyer un mail si c'est toi-même qui te connectes (Optionnel)
            // Si tu veux être notifié même pour toi-même, enlève cette condition "if ($user->id !== 1)"
            if ($superAdmin) {
                $superAdmin->notify(new AdminLoginAlert($user, Request::ip()));
            }
        }
    }
}