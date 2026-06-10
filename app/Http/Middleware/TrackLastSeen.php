<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class TrackLastSeen
{
    /**
     * Met à jour `last_seen_at` de l'utilisateur connecté à chaque requête.
     *
     * PERFORMANCE : pour ne pas écrire en base à CHAQUE requête, on throttle
     * via le cache Redis : une seule mise à jour par utilisateur et par minute.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            $cacheKey = "user-last-seen:{$user->id}";

            // Si la clé n'existe pas encore, on enregistre l'activité.
            // add() est atomique : il pose la clé seulement si absente.
            if (Cache::add($cacheKey, true, now()->addMinute())) {
                $user->forceFill(['last_seen_at' => now()])->saveQuietly();
            }
        }

        return $next($request);
    }
}
