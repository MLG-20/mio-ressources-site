<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semestre extends Model
{
    protected $fillable = ['nom', 'niveau', 'image_path'];
    
    public function matieres() { return $this->hasMany(Matiere::class); }
}
