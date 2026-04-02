<?php

namespace App\Observers;

use App\Models\Ressource;
use App\Models\User;
use App\Notifications\NewResourceNotification;

class RessourceObserver
{
    /**
     * Se déclenche juste après qu'une ressource soit créée dans la base de données
     */
    public function created(Ressource $ressource): void
{
    $ressource->load(['matiere.semestre']);
    $niveau = $ressource->matiere->semestre->niveau;

    // LOGIQUE INTELLIGENTE : 
    // On n'envoie un MAIL que pour les TD (corrigés ou non) et les Vidéos (Tutos)
    if ($ressource->type === 'TD' || $ressource->type === 'Vidéo') {

        User::where('student_level', $niveau)
            ->chunk(100, function ($students) use ($ressource) {
                foreach ($students as $student) {
                    $student->notify(new NewResourceNotification($ressource));
                }
            });
    }
    
    // S'il s'agit d'un "Cours", on ne fait rien ici. 
    // Il apparaîtra automatiquement dans la boîte bleue de l'étudiant 
    // sans le déranger par mail.
}

    /**
     * Optionnel : Tu peux aussi envoyer un mail si une ressource est mise à jour
     */
    public function updated(Ressource $ressource): void
    {
        // Logique similaire si besoin
    }
}