<table class="subcopy" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td>
            @php
                // On convertit le slot en texte brut
                $text = (string) $slot;

                // On utilise une expression régulière (Regex) pour trouver la phrase anglaise
                // Le \s+ permet de capturer les espaces ET les retours à la ligne cachés
                $text = preg_replace(
                    '/If you\'re having trouble clicking the "([^"]+)" button, copy and paste the URL below\s+into your web browser:/',
                    'Si vous ne parvenez pas à cliquer sur le bouton "$1", copiez et collez l\'URL ci-dessous dans votre navigateur :',
                    $text
                );
            @endphp

            {{ Illuminate\Mail\Markdown::parse($text) }}
        </td>
    </tr>
</table>