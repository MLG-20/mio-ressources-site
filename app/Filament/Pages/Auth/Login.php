<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    protected function throwFailureValidationException(): never
    {
        // Vérifier si l'utilisateur existe et est bloqué
        $email = $this->getCredentialsFromFormData($this->form->getState())['email'] ?? null;

        if ($email) {
            $user = \App\Models\User::where('email', $email)->first();

            if ($user && $user->is_blocked) {
                throw ValidationException::withMessages([
                    'data.email' => 'Votre compte a été suspendu par l\'administrateur. Veuillez le contacter pour plus d\'informations.',
                ]);
            }
        }

        throw ValidationException::withMessages([
            'data.email' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }
}
