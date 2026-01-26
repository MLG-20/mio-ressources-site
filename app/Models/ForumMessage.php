<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumMessage extends Model
{
    //
    protected $fillable = ['forum_sujet_id', 'user_id', 'contenu'];
public function sujet() { return $this->belongsTo(ForumSujet::class, 'forum_sujet_id'); }
public function user() { return $this->belongsTo(User::class); }
}
