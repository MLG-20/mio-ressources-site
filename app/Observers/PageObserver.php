<?php

namespace App\Observers;

use App\Models\Page;
use App\Models\PageVersion;

class PageObserver
{
    /**
     * Handle the Page "updating" event. (avant la sauvegarde)
     */
    public function updating(Page $page): void
    {
        // Si c'est une page légale,enregistre la version antérieure
        $legalSlugs = ['cgu', 'mentions-legales', 'politique-confidentialite'];

        if (in_array($page->slug, $legalSlugs)) {
            // Récupère l'ancienne version de la page
            $oldPage = Page::find($page->id);

            if ($oldPage && ($oldPage->contenu !== $page->contenu || $oldPage->titre !== $page->titre)) {
                PageVersion::create([
                    'page_id' => $page->id,
                    'contenu_ancien' => $oldPage->contenu,
                    'contenu_nouveau' => $page->contenu,
                    'titre_ancien' => $oldPage->titre,
                    'titre_nouveau' => $page->titre,
                    'user_id' => auth()?->id(),
                    'description_changement' => 'Modification automatique enregistrée',
                ]);
            }
        }
    }
}
