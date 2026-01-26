<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    // On autorise ces deux champs
    protected $fillable = ['ip_address', 'page_visited'];

    // DÉSACTIVER LES TIMESTAMPS PAR DÉFAUT (created_at / updated_at)
    // C'est ce qui causait ton erreur
    public $timestamps = false;
}