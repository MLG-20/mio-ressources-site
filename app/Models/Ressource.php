<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ressource extends Model
{
    // On précise le nom de la table si elle est au pluriel avec deux "s"
    protected $table = 'ressources';

    protected $fillable = ['titre', 'type', 'file_path', 'matiere_id', 'is_premium',  'price'];

    // Une ressource appartient à une matière
    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    /**
     * Relation : La ressource appartient à un utilisateur (Admin ou Prof)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}