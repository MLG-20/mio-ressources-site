<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Auth\Passwords\CanResetPassword;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail, CanResetPasswordContract
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
        'avatar',        // Photo de profil
        'user_type',     // student, teacher
        'student_level', // L1, L2, L3
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
        ];
    }

    /**
     * SÉCURITÉ DASHBOARD ADMIN (Filament)
     * On restreint l'accès UNIQUEMENT à l'admin principal.
     */
    public function isSuperAdmin(): bool
    {
        $adminEmail = env('ADMIN_EMAIL');
        return $this->role === 'admin'
            && ($adminEmail === null || $this->email === $adminEmail);
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
