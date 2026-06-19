<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirige l'utilisateur vers l'écran de consentement Google.
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Retour de Google (callback).
     * - Si le compte est déjà lié (google_id) ou existe par e-mail : connexion.
     * - Sinon : on mémorise les infos Google et on demande le type de compte.
     */
    public function callback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Échec connexion Google', [
                'exception' => get_class($e),
                'message'   => $e->getMessage(),
            ]);

            return redirect()->route('login')->withErrors([
                'email' => "La connexion Google a échoué. Veuillez réessayer.",
            ]);
        }

        // a) Compte déjà lié à ce compte Google.
        $user = User::where('google_id', $googleUser->getId())->first();

        // b) Sinon, compte existant avec le même e-mail (vérifié par Google) : on le lie.
        if (! $user) {
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                $user->forceFill([
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $user->avatar ?: $googleUser->getAvatar(),
                ])->save();
            }
        }

        // c) Aucun compte : nouveau visiteur -> il faut connaître le type (étudiant/prof).
        if (! $user) {
            $request->session()->put('google_oauth', [
                'google_id' => $googleUser->getId(),
                'name'      => $googleUser->getName(),
                'email'     => $googleUser->getEmail(),
                'avatar'    => $googleUser->getAvatar(),
            ]);

            return redirect()->route('google.choose-type');
        }

        return $this->loginAndRedirect($user);
    }

    /**
     * Affiche le choix « Vous êtes ? » pour un nouveau compte Google.
     */
    public function chooseType(Request $request): View|RedirectResponse
    {
        $google = $request->session()->get('google_oauth');

        if (! $google) {
            return redirect()->route('register');
        }

        return view('auth.google-choose-type', [
            'name'  => $google['name'],
            'email' => $google['email'],
        ]);
    }

    /**
     * Crée le compte Google selon le type choisi, puis connecte l'utilisateur.
     * Réplique la logique de RegisteredUserController::store.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_type'     => ['required', 'in:student,teacher'],
            'student_level' => ['nullable', 'required_if:user_type,student', 'in:L1,L2,L3'],
        ]);

        $google = $request->session()->get('google_oauth');

        if (! $google) {
            return redirect()->route('register');
        }

        // Garde-fou : e-mail pris entre-temps (double onglet, etc.).
        if (User::where('email', $google['email'])->exists()) {
            $request->session()->forget('google_oauth');

            return redirect()->route('login')->withErrors([
                'email' => "Un compte existe déjà avec cet e-mail. Veuillez vous connecter.",
            ]);
        }

        $isStudent = $request->user_type === 'student';
        $role = $isStudent ? 'etudiant' : 'professeur';

        $user = User::create([
            'name'          => $google['name'] ?: $google['email'],
            'email'         => $google['email'],
            'google_id'     => $google['google_id'],
            'avatar'        => $google['avatar'],
            'password'      => null, // compte Google : pas de mot de passe
            'user_type'     => $request->user_type,
            'student_level' => $isStudent ? $request->student_level : null,
            'trial_ends_at' => $isStudent ? now()->addMonths(3) : null,
            'role'          => $role,
        ]);

        // Google a déjà vérifié l'e-mail (email_verified_at n'est pas mass-assignable).
        $user->forceFill(['email_verified_at' => now()])->save();

        $request->session()->forget('google_oauth');
        event(new Registered($user));

        return $this->loginAndRedirect($user);
    }

    /**
     * Connecte l'utilisateur et le redirige selon son rôle.
     * Cohérent avec AuthenticatedSessionController (blocage staff/prof, anti-/admin).
     */
    protected function loginAndRedirect(User $user): RedirectResponse
    {
        // Blocage strict pour les comptes staff/prof (les étudiants passent par l'abonnement).
        if ($user->is_blocked && $user->user_type !== 'student') {
            return redirect()->route('login')->withErrors([
                'email' => "Votre compte a été suspendu par l'administrateur. Veuillez le contacter pour plus d'informations.",
            ]);
        }

        Auth::login($user, remember: true);

        if ($user->role === 'admin') {
            return redirect()->intended('/admin');
        }

        if ($user->role === 'professeur') {
            return redirect()->intended('/espace-enseignant');
        }

        return redirect()->intended(route('user.dashboard', absolute: false));
    }
}
