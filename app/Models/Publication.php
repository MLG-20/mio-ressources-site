<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Publication extends Model
{
    use HasFactory;

    // C'EST CETTE LIGNE QUI MANQUAIT
    protected $fillable = [
        'titre', 
        'type', 
        'description', 
        'file_path', 
        'cover_image', 
        'user_id', 
        'is_verified',
        'is_premium', // Pour la vente
        'price',      // Pour le prix
    ];

    /**
     * Relation : Une publication appartient à un utilisateur (Prof)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ratings() { return $this->hasMany(ResourceRating::class); }
}