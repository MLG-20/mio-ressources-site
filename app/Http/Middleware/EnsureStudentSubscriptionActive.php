<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentSubscriptionActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->user_type !== 'student') {
            return $next($request);
        }

        $trialEndsAt = $user->trial_ends_at ?? $user->created_at?->copy()->addMonths(3);
        $isInTrial = $trialEndsAt && now()->lessThanOrEqualTo($trialEndsAt);
        $hasActiveSubscription = $user->subscription_paid_until
            && now()->lessThanOrEqualTo($user->subscription_paid_until);

        if ($isInTrial || $hasActiveSubscription) {
            return $next($request);
        }

        return redirect()
            ->route('student.subscription.paywall')
            ->with('error', 'Votre période gratuite de 3 mois est terminée. Activez votre abonnement (500 F) pour accéder à votre espace étudiant.');
    }
}
