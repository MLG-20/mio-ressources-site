<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureRole;
use App\Http\Middleware\EnsureStudentSubscriptionActive;
use App\Http\Middleware\TrackLastSeen;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Faire confiance à tous les proxies (ngrok en dev, reverse proxy en prod)
        $middleware->trustProxies(at: '*');

        // Suivi de la dernière activité (détection des utilisateurs en ligne)
        $middleware->web(append: [
            TrackLastSeen::class,
        ]);

        // SÉCURITÉ : middleware d'autorisation basé sur le rôle (admin/professeur/etudiant)
        // Utilisation: ->middleware('role:professeur')
        $middleware->alias([
            'role' => EnsureRole::class,
            'student.subscription' => EnsureStudentSubscriptionActive::class,
        ]);

        // On autorise PayTech à nous envoyer des données sans token de sécurité interne
        $middleware->validateCsrfTokens(except: [
            'api/paytech/callback',
            'api/paytech/subscription-callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Intégration Sentry (optionnel — décommenter après avoir installé sentry/sentry-laravel)
        // $exceptions->report(function (\Throwable $e) {
        //     if (app()->bound('sentry') && $this->shouldReport($e)) {
        //         app('sentry')->captureException($e);
        //     }
        // });
    })->create();
