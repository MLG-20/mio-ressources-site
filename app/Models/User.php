<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Filament\Models\Contracts\FilamentUser; 
use Filament\Panel; 

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    /**
     * CHAMPS AUTORISÉS (Mass Assignment)
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',          // admin, professeur, etudiant
        'avatar',        // Photo de profil
        'user_type',     // student, teacher
        'student_level', // L1, L2, L3
        'specialty',     // Spécialité du prof
        'wallet_balance', // SOLDE DES REVENUS (Très important !)
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * SÉCURITÉ DASHBOARD ADMIN (Filament)
     * On restreint l'accès UNIQUEMENT à l'admin principal.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Désormais, seul l'admin MLG entre dans le dashboard noir.
        // Le professeur ira sur son propre espace (/espace-enseignant).
        return $this->role === 'admin';
    }

    /**
     * NOTIFICATION : Réinitialisation de mot de passe personnalisée
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new class($token) extends ResetPassword {
            public function toMail($notifiable)
            {
                return (new MailMessage)
                    ->subject('Réinitialisation de votre mot de passe - MIO')
                    ->greeting('Bonjour ' . $notifiable->name . ' !')
                    ->line('Vous recevez cet email car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte sur MIO Ressources.')
                    ->action('Réinitialiser mon mot de passe', url(config('app.url').route('password.reset', $this->token, false)))
                    ->line('Ce lien de réinitialisation expirera dans 60 minutes.')
                    ->line('Si vous n\'avez pas demandé de réinitialisation, aucune action supplémentaire n\'est requise.')
                    ->salutation('L\'équipe MIO Ressources');
            }
        });
    }

    /**
     * HELPER : Vérifier si c'est un enseignant
     */
    public function isTeacher() 
    {
        return $this->user_type === 'teacher';
    }
}