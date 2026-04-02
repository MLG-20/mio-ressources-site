<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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

    $user = auth()->user();

    // Compte bloqué par l'administrateur
    if ($user->is_blocked) {
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
