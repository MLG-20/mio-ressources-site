<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownloadHistory extends Model
{
    public $timestamps = false; // On gère nous-même la date si besoin, ou on utilise created_at

    protected $fillable = ['user_id', 'ressource_id', 'downloaded_at'];

    // Relation vers la ressource
    public function ressource()
    {
        return $this->belongsTo(Ressource::class);
    }
}