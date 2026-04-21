<?php

namespace App\Support;

class HtmlSanitizer
{
    public static function clean(string $html): string
    {
        return strip_tags($html, '<iframe><p><br><b><i><u><a><ul><ol><li><strong><em><span><div><img><h1><h2><h3><h4><h5><h6>');
    }

    // Ajoute cette méthode pour corriger l'erreur de Page.php:23
    public static function sanitizeRichText(string $html): string
    {
        return self::clean($html);
    }

    public static function sanitizeGoogleMapsEmbed(string $html): string
    {
        if (preg_match('/<iframe[^>]+src=["\\\']https:\/\/(www\\.)?google\\.com\/maps\/embed[^"\\\']*["\\\'][^>]*><\\/iframe>/i', $html, $matches)) {
            return $matches[0];
        }
        return self::clean($html);
    }
}
