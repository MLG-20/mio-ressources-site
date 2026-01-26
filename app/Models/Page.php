<?php

namespace App\Models;

use App\Support\HtmlSanitizer;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    //
    protected $fillable = ['titre', 'slug', 'contenu', 'cover_image'];

    /**
     * SÉCURITÉ (XSS) :
     * On sanitise automatiquement le HTML des pages CMS avant sauvegarde.
     * Ainsi, l'affichage Blade en `{!! $page->contenu !!}` reste possible sans
     * exposer le site aux scripts/attributs dangereux.
     */
    protected static function booted(): void
    {
        static::saving(function (self $page): void {
            $page->contenu = HtmlSanitizer::sanitizeRichText($page->contenu);
        });
    }
}
