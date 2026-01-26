<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    // AUTORISER L'ENREGISTREMENT DE CES CHAMPS
    protected $fillable = [
        'user_id',
        'guest_email', 
        'ressource_id',
        'publication_id', // AJOUTÉ
        'item_type',      // AJOUTÉ 
        'amount', 
        'payment_id'
    ];
    
    public function publication()
{
    return $this->belongsTo(Publication::class);
}

    /**
     * Relation : L'achat appartient à un utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : L'achat concerne une ressource (cours)
     */
    public function ressource()
    {
        return $this->belongsTo(Ressource::class);
    }
}