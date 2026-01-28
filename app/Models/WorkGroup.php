<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WorkGroup extends Model
{
    protected $fillable = [
        'name',
        'description',
        'room_name',
        'creator_id',
    ];

    /**
     * Génération automatique du room_name unique pour Jitsi
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($group) {
            if (empty($group->room_name)) {
                $group->room_name = 'GROUP-' . Str::upper(Str::random(8));
            }
        });
    }

    /**
     * RELATION : Créateur du groupe
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * RELATION : Membres du groupe (many-to-many)
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'group_user')
                    ->withTimestamps();
    }

    /**
     * HELPER : Vérifier si un utilisateur est membre
     */
    public function hasMember($userId)
    {
        return $this->members()->where('user_id', $userId)->exists();
    }
}
