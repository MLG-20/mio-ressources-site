<?php

// Fichier de diagnostic pour les pages
// À ajouter dans routes/web.php si vous avez besoin de déboguer

Route::get('/debug-pages', function () {
    $pages = \App\Models\Page::all();

    echo "<h1>DEBUG PAGES</h1>";

    foreach ($pages as $page) {
        echo "<hr>";
        echo "<h2>Slug: {$page->slug}</h2>";
        echo "<p>Titre: {$page->titre}</p>";
        echo "<p>Contenu (caractères): " . strlen($page->contenu ?? '') . "</p>";

        if ($page->contenu) {
            echo "<p>Contenu (aperçu):</p>";
            echo "<textarea cols='100' rows='10'>" . htmlspecialchars($page->contenu) . "</textarea>";

            // Vérifier les images
            if (preg_match_all('/<img[^>]+src=(["\'])([^"\']+)\1/i', $page->contenu, $matches)) {
                echo "<p><strong>Images trouvées:</strong></p>";
                foreach ($matches[2] as $imgSrc) {
                    echo "<p>- $imgSrc</p>";
                    $fullPath = public_path($imgSrc);
                    if (file_exists($fullPath)) {
                        echo " <span style='color: green;'>✓ FICHIER EXISTE</span>";
                    } else {
                        echo " <span style='color: red;'>✗ FICHIER MANQUANT</span>";
                    }
                    echo "</p>";
                }
            } else {
                echo "<p>Aucune image trouvée</p>";
            }
        }
    }
});
