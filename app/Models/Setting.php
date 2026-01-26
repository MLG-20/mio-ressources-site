<?php

namespace App\Models;

use App\Support\HtmlSanitizer;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    //
    protected $fillable = ['key', 'value', 'label'];

    /**
     * SÉCURITÉ (XSS) :
     * On sanitize certains réglages qui contiennent du HTML.
     * Exemple critique : `univ_map` (embed Google Maps) est affiché en `{!! !!}`.
     */
    protected static function booted(): void
    {
        static::saving(function (self $setting): void {
            if ($setting->key === 'univ_map') {
                $setting->value = HtmlSanitizer::sanitizeGoogleMapsEmbed($setting->value);
            }
        });
    }
}
