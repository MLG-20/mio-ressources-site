<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Slider extends Model
{
    //
    protected $fillable = ['image_path', 'video_path', 'titre', 'description', 'ordre'];

    protected static function booted(): void
    {
        // Le hero d'accueil met les sliders en cache 1h (voir HomeController).
        // On vide ce cache dès qu'un slider change, pour que les nouveautés
        // (image ou vidéo) apparaissent immédiatement sur l'accueil.
        $forget = static fn () => Cache::forget('sliders');

        static::saved($forget);
        static::deleted($forget);
    }
}
