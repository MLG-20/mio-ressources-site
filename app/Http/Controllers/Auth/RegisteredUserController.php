<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'user_type' => ['required', 'string'],
    ]);

    // LOGIQUE DE RÔLE : 
    // Si l'utilisateur a choisi "teacher", on lui donne le rôle 'professeur'
    $role = ($request->user_type === 'teacher') ? 'professeur' : 'etudiant';

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'user_type' => $request->user_type,
        'student_level' => $request->student_level,
        'trial_ends_at' => $request->user_type === 'student' ? now()->addMonths(3) : null,
        'role' => $role, // On enregistre le rôle ici
    ]);

    event(new Registered($user));

    Auth::login($user);

    // REDIRECTION INTELLIGENTE :
    if ($user->user_type === 'teacher') {
        // SÉCURITÉ : le professeur ne doit pas être envoyé vers /admin (réservé au rôle admin)
        return redirect('/espace-enseignant');
    }

    return redirect(route('user.dashboard', absolute: false)); // L'étudiant va vers son espace
}
}
