<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumSujet extends Model
{
    //
    public function messages()
{
    return $this->hasMany(ForumMessage::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}

public function category()
{
    return $this->belongsTo(ForumCategory::class, 'forum_category_id');
}
}
