<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Matiere extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'nom', 'semestre_id'];

    // Relation avec le semestre (déjà faite normalement)
    public function semestre()
    {
        return $this->belongsTo(Semestre::class);
    }

    // AJOUTE CETTE RELATION :
    // Une matière possède plusieurs ressources (PDF, Vidéos, etc.)
    public function ressources()
    {
        return $this->hasMany(Ressource::class);
    }
}