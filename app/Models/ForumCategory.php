<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumCategory extends Model
{
    //
    protected $fillable = ['nom', 'description', 'ordre'];
public function sujets() { return $this->hasMany(ForumSujet::class); }
}
