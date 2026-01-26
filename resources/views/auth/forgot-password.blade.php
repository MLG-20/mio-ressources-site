<x-guest-layout>
    
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-900 tracking-tighter uppercase mb-3">Mot de passe oublié ?</h1>
        <p class="text-slate-500 text-sm font-medium leading-relaxed">
            Pas de panique. Indiquez simplement votre adresse email universitaire et nous vous enverrons un lien sécurisé pour en choisir un nouveau.
        </p>
    </div>

    <!-- Message de succès (Session Status) -->
    <x-auth-session-status class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl font-bold border border-green-100 text-sm" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-[10px] font-black uppercase text-slate-400 mb-2 ml-1">Votre Email</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-slate-300 group-focus-within:text-blue-500 transition-colors"></i>
                </div>
                <input id="email" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl py-3 pl-11 pr-4 font-bold text-slate-700 outline-none focus:border-blue-500 focus:bg-white transition-all input-premium" 
                       type="email" name="email" :value="old('email')" required autofocus placeholder="etudiant@uidt.sn">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-bold text-red-500" />
        </div>

        <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl hover:bg-blue-600 transition-all transform active:scale-95 flex items-center justify-center gap-2 group">
            <span>Envoyer le lien</span>
            <i class="fas fa-paper-plane group-hover:translate-x-1 transition-transform"></i>
        </button>
    </form>
    
    <div class="mt-8 text-center">
        <a href="{{ route('login') }}" class="text-xs font-bold text-slate-400 hover:text-slate-600 transition uppercase tracking-widest flex items-center justify-center gap-2">
            <i class="fas fa-arrow-left"></i> Retour à la connexion
        </a>
    </div>

</x-guest-layout>