<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ressource extends Model
{
    protected $table = 'ressources';

    protected $fillable = ['titre', 'type', 'file_path', 'matiere_id', 'is_premium', 'price', 'user_id'];

    protected $hidden = ['file_path'];

    protected static function booted(): void
    {
        static::creating(function ($ressource) {
            if (auth()->check() && !$ressource->user_id) {
                $ressource->user_id = auth()->id();
            }
        });
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
