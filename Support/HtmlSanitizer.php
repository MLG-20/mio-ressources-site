<?php

namespace App\Support;

class HtmlSanitizer
{
    /**
     * SÉCURITÉ (XSS) :
     * Nettoie un HTML issu d'un éditeur riche (Filament RichEditor) afin d'éviter
     * l'injection de scripts / attributs dangereux.
     *
     * Objectif : conserver du formatage (titres, listes, liens, images) tout en
     * supprimant tout ce qui permet d'exécuter du JavaScript.
     */
    public static function sanitizeRichText(?string $html): string
    {
        $html = (string) ($html ?? '');

        if (trim($html) === '') {
            return '';
        }

        // Autorisation de base par tags : (les attributs seront filtrés ensuite)
        $allowedTags = [
            'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's',
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'blockquote', 'pre', 'code',
            'ul', 'ol', 'li',
            'a', 'img', 'figure', 'figcaption',
            'hr', 'span', 'div',
        ];

        // 1) On retire les tags non autorisés (conserve le texte)
        $html = self::stripTagsKeepContent($html, $allowedTags);

        // 2) On retire tout attribut "on*" (onclick, onerror, ...)
        $html = preg_replace('/\son[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html) ?? $html;

        // 3) On neutralise les URLs dangereuses (javascript:, data:)
        $html = self::sanitizeUrls($html);

        // 4) On filtre les attributs acceptés (whitelist) sur a/img (évite style, srcset, etc.)
        $html = self::filterAttributes($html);

        return $html;
    }

    /**
     * SÉCURITÉ (XSS) :
     * Autorise uniquement un embed Google Maps (iframe) et supprime le reste.
     */
    public static function sanitizeGoogleMapsEmbed(?string $html): string
    {
        $html = (string) ($html ?? '');

        if (trim($html) === '') {
            return '';
        }

        // On ne garde que les iframes.
        $html = strip_tags($html, '<iframe>');

        // Extraction du 1er iframe.
        if (! preg_match('/<iframe\b[^>]*>\s*<\/iframe>/i', $html, $matches)) {
            return '';
        }

        $iframe = $matches[0];

        // Récupération du src.
        if (! preg_match('/\ssrc\s*=\s*("([^"]*)"|\'([^\']*)\')/i', $iframe, $srcMatch)) {
            return '';
        }

        $src = $srcMatch[2] ?: ($srcMatch[3] ?? '');
        $src = trim($src);

        // SÉCURITÉ : on autorise uniquement Google Maps.
        $allowedPrefixes = [
            'https://www.google.com/maps',
            'https://maps.google.com/maps',
            'https://www.google.com/maps/embed',
        ];

        $isAllowed = false;
        foreach ($allowedPrefixes as $prefix) {
            if (str_starts_with($src, $prefix)) {
                $isAllowed = true;
                break;
            }
        }

        if (! $isAllowed) {
            return '';
        }

        // On supprime les attributs dangereux et on garde une whitelist minimale.
        $iframe = preg_replace('/\son[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $iframe) ?? $iframe;

        // Garde seulement certains attributs.
        $iframe = preg_replace_callback(
            '/<iframe\b([^>]*)>/i',
            function ($m) {
                $attrs = $m[1] ?? '';

                $allowed = [];
                foreach (['src', 'width', 'height', 'style', 'loading', 'referrerpolicy', 'allowfullscreen'] as $attr) {
                    if (preg_match('/\s' . preg_quote($attr, '/') . '\s*=\s*("[^"]*"|\'[^\']*\')/i', $attrs, $am)) {
                        $allowed[] = $attr . '=' . $am[1];
                    } elseif ($attr === 'allowfullscreen' && preg_match('/\sallowfullscreen\b/i', $attrs)) {
                        $allowed[] = 'allowfullscreen';
                    }
                }

                return '<iframe ' . implode(' ', $allowed) . '>';
            },
            $iframe
        ) ?? $iframe;

        return $iframe;
    }

    private static function stripTagsKeepContent(string $html, array $allowedTags): string
    {
        $allowed = '<' . implode('><', $allowedTags) . '>';
        return strip_tags($html, $allowed);
    }

    private static function sanitizeUrls(string $html): string
    {
        // Neutralise href/src contenant javascript: ou data:
        $html = preg_replace('/\s(href|src)\s*=\s*("|\')\s*(javascript:|data:)[^"\']*("|\')/i', ' $1="#"', $html) ?? $html;
        return $html;
    }

    private static function filterAttributes(string $html): string
    {
        // Nettoyage des <a ...>
        $html = preg_replace_callback('/<a\b([^>]*)>/i', function ($m) {
            $attrs = $m[1] ?? '';

            $allowed = [];
            foreach (['href', 'title', 'target', 'rel'] as $attr) {
                if (preg_match('/\s' . preg_quote($attr, '/') . '\s*=\s*("[^"]*"|\'[^\']*\')/i', $attrs, $am)) {
                    $allowed[] = $attr . '=' . $am[1];
                }
            }

            // SÉCURITÉ : si target="_blank", forcer rel="noopener noreferrer"
            $hasBlank = false;
            foreach ($allowed as $a) {
                if (stripos($a, 'target=') === 0 && stripos($a, '"_blank"') !== false) {
                    $hasBlank = true;
                    break;
                }
            }
            if ($hasBlank) {
                $allowed = array_filter($allowed, fn ($a) => stripos($a, 'rel=') !== 0);
                $allowed[] = 'rel="noopener noreferrer"';
            }

            return '<a' . (count($allowed) ? ' ' . implode(' ', $allowed) : '') . '>';
        }, $html) ?? $html;

        // Nettoyage des <img ...>
        $html = preg_replace_callback('/<img\b([^>]*)>/i', function ($m) {
            $attrs = $m[1] ?? '';

            $allowed = [];
            foreach (['src', 'alt', 'title', 'width', 'height', 'loading'] as $attr) {
                if (preg_match('/\s' . preg_quote($attr, '/') . '\s*=\s*("[^"]*"|\'[^\']*\')/i', $attrs, $am)) {
                    $allowed[] = $attr . '=' . $am[1];
                }
            }

            return '<img' . (count($allowed) ? ' ' . implode(' ', $allowed) : '') . '>';
        }, $html) ?? $html;

        return $html;
    }
}
