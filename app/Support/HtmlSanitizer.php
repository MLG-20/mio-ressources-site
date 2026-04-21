<?php

namespace App\Support;

class HtmlSanitizer
{
    /**
     * Nettoie le HTML en autorisant uniquement certaines balises de base.
     * @param string $html
     * @return string
     */
    public static function clean(string $html): string
    {
        // Autorise les balises de base (iframe, p, br, b, i, u, a, ul, ol, li, strong, em, etc.)
        return strip_tags($html, '<iframe><p><br><b><i><u><a><ul><ol><li><strong><em><span><div><img><h1><h2><h3><h4><h5><h6>');
    }

    /**
     * Nettoie et valide un embed Google Maps (iframe uniquement, src sécurisé).
     * @param string $html
     * @return string
     */
    public static function sanitizeGoogleMapsEmbed(string $html): string
    {
        // On autorise uniquement les iframes Google Maps
        if (preg_match('/<iframe[^>]+src=["\\\']https:\/\/(www\\.)?google\\.com\/maps\/embed[^"\\\']*["\\\'][^>]*><\\/iframe>/i', $html, $matches)) {
            return $matches[0];
        }
        // Sinon, nettoyage classique
        return self::clean($html);
    }
}
