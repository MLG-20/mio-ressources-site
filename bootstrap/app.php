<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureRole;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // SÉCURITÉ : middleware d'autorisation basé sur le rôle (admin/professeur/etudiant)
        // Utilisation: ->middleware('role:professeur')
        $middleware->alias([
            'role' => EnsureRole::class,
        ]);

        // On autorise PayTech à nous envoyer des données sans token de sécurité interne
        $middleware->validateCsrfTokens(except: [
            'api/paytech/callback',
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
