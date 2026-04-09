<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();

    /** @var User $user */
    $user = Auth::user();

    // Blocage admin strict uniquement pour les comptes staff/prof.
    // Les étudiants doivent passer par le flux abonnement (middleware student.subscription).
    if ($user->is_blocked && $user->user_type !== 'student') {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()->withErrors([
            'email' => 'Votre compte a été suspendu par l\'administrateur. Veuillez le contacter pour plus d\'informations.',
        ])->onlyInput('email');
    }

    // SÉCURITÉ : redirection selon le rôle (évite d'envoyer un professeur sur /admin)
    if ($user->role === 'admin') {
        return redirect()->intended('/admin');
    }

    if ($user->role === 'professeur') {
        return redirect()->intended('/espace-enseignant');
    }

    // C'est un Étudiant -> Direction son Espace Personnel
    return redirect()->intended(route('user.dashboard', absolute: false));
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
