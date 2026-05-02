<?php

namespace App\Support;

class HtmlSanitizer
{
    public static function sanitizeRichText(string $html): string
    {
        return clean($html, [
            'HTML.Allowed' => 'p,br,b,i,u,a[href|title|target],ul,ol,li,strong,em,span,div,img[src|alt|width|height],h1,h2,h3,h4,h5,h6',
            'HTML.ForbiddenAttributes' => 'style,class',
            'AutoFormat.AutoParagraph' => false,
            'AutoFormat.RemoveEmpty' => true,
        ]);
    }

    public static function sanitizeGoogleMapsEmbed(string $html): string
    {
        if (preg_match('/<iframe[^>]+src=["\']https:\/\/(www\.)?google\.com\/maps\/embed[^"\']*["\'][^>]*><\/iframe>/i', $html, $matches)) {
            return $matches[0];
        }
        return self::sanitizeRichText($html);
    }
}
