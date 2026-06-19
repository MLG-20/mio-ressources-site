<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Auth\Passwords\CanResetPassword;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser, CanResetPasswordContract
{
    use HasFactory, Notifiable, CanResetPassword;

    /**
     * CHAMPS AUTORISÉS (Mass Assignment)
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',          // admin, professeur, etudiant
        'google_id',     // Identifiant du compte Google (connexion OAuth)
        'avatar',        // Photo de profil
        'user_type',     // student, teacher
        'student_level', // L1, L2, L3
        'trial_ends_at',
        'subscription_paid_until',
        'specialty',     // Spécialité du prof
        'permissions',   // JSON — sections autorisées pour les sous-admins
        'is_blocked',    // Bloquer l'accès au panel
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'permissions'       => 'array',
            'is_blocked'        => 'boolean',
            'trial_ends_at'     => 'datetime',
            'subscription_paid_until' => 'datetime',
            'last_seen_at'      => 'datetime',
        ];
    }

    /**
     * UTILISATEURS EN LIGNE
     * Délai d'inactivité (en minutes) au-delà duquel on considère
     * un utilisateur comme déconnecté.
     */
    public const ONLINE_THRESHOLD_MINUTES = 5;

    /**
     * L'utilisateur est-il actuellement en ligne ?
     */
    public function isOnline(): bool
    {
        return $this->last_seen_at !== null
            && $this->last_seen_at->gt(now()->subMinutes(self::ONLINE_THRESHOLD_MINUTES));
    }

    /**
     * Scope : uniquement les utilisateurs actifs récemment.
     * Usage : User::online()->get()
     */
    public function scopeOnline($query)
    {
        return $query->where('last_seen_at', '>', now()->subMinutes(self::ONLINE_THRESHOLD_MINUTES));
    }

    /**
     * SÉCURITÉ DASHBOARD ADMIN (Filament)
     * On restreint l'accès UNIQUEMENT à l'admin principal.
     */
    public function isSuperAdmin(): bool
    {
        $adminEmail = config('app.admin_email');
        return $this->role === 'admin'
            && $adminEmail !== null
            && $this->email === $adminEmail;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Bloqué = accès refusé
        if ($this->is_blocked) {
            return false;
        }

        // Super admin : accès total
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Sous-admin : role=admin mais pas le super admin
        return $this->role === 'admin';
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        return in_array($permission, $this->permissions ?? []);
    }

    /**
     * NOTIFICATION : Réinitialisation de mot de passe personnalisée
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * HELPER : Vérifier si c'est un enseignant
     */
    public function isTeacher()
    {
        return $this->user_type === 'teacher';
    }

    /**
     * Relations
     */
    public function ressources()
    {
        return $this->hasMany(\App\Models\Ressource::class);
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    public function enrolledMeetings()
    {
        return $this->belongsToMany(Meeting::class, 'meeting_enrollments')->withTimestamps();
    }

    /**
     * RELATION : Inscriptions aux cours particuliers (pour étudiants)
     */
    public function enrollments()
    {
        return $this->hasMany(PrivateLessonEnrollment::class, 'student_id');
    }

    /**
     * RELATION : Groupes de travail dont l'utilisateur est membre
     */
    public function workGroups()
    {
        return $this->belongsToMany(WorkGroup::class, 'group_user')
                    ->withTimestamps();
    }

    /**
     * RELATION : Groupes de travail créés par l'utilisateur
     */
    public function createdWorkGroups()
    {
        return $this->hasMany(WorkGroup::class, 'creator_id');
    }
}
