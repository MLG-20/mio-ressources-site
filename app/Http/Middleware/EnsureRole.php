<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * SÉCURITÉ :
     * Ce middleware bloque l'accès aux routes si l'utilisateur connecté
     * n'a pas le rôle requis (admin/professeur/etudiant).
     *
     * Utilisation: ->middleware('role:professeur')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // SÉCURITÉ : Si pas authentifié, on renvoie 403 (les routes doivent déjà être sous auth).
        if (! $user) {
            abort(403, 'Accès refusé.');
        }

        // SÉCURITÉ : on compare strictement au champ 'role' défini en base.
        if (! in_array($user->role, $roles, true)) {
            abort(403, 'Accès réservé.');
        }

        return $next($request);
    }
}
