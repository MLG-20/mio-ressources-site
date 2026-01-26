<x-guest-layout>
    
    <div class="mb-10">
        <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase mb-2">Bon retour</h1>
        <p class="text-slate-600 font-bold text-base">
            Pas encore de compte ?
            <a href="{{ route('register') }}" class="text-blue-700 hover:text-blue-900 underline decoration-2 underline-offset-4 transition">
                Créer un compte gratuitement
            </a>
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-xs font-black uppercase text-slate-600 mb-2 ml-1 tracking-wider">Adresse Email</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-slate-400 group-focus-within:text-blue-600 text-lg transition-colors"></i>
                </div>
                <input id="email" name="email" type="email" autocomplete="email" required 
                       class="pl-12 block w-full bg-white border-2 border-slate-300 rounded-xl text-slate-900 font-bold text-lg py-4 shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-600 transition-all placeholder:text-slate-400" 
                       placeholder="etudiant@uidt.sn">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm font-bold text-red-600" />
        </div>

        <!-- Mot de passe -->
        <div>
            <div class="flex justify-between items-center mb-2 ml-1">
                <label for="password" class="block text-xs font-black uppercase text-slate-600 tracking-wider">Mot de passe</label>
                <a href="{{ route('password.request') }}" class="text-xs font-bold text-blue-700 hover:text-blue-900 hover:underline">Mot de passe oublié ?</a>
            </div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-slate-400 group-focus-within:text-blue-600 text-lg transition-colors"></i>
                </div>
                <input id="password" name="password" type="password" required autocomplete="current-password"
                       class="pl-12 block w-full bg-white border-2 border-slate-300 rounded-xl text-slate-900 font-bold text-lg py-4 shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-600 transition-all placeholder:text-slate-400" 
                       placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm font-bold text-red-600" />
        </div>

        <!-- Remember Me (Plus visible) -->
        <div class="flex items-center ml-1">
            <input id="remember_me" name="remember" type="checkbox" class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-slate-300 rounded cursor-pointer">
            <label for="remember_me" class="ml-3 block text-sm font-bold text-slate-700 cursor-pointer">Se souvenir de moi</label>
        </div>

        <!-- Bouton -->
        <button type="submit" class="w-full flex justify-center py-4 px-6 border border-transparent rounded-xl shadow-lg text-lg font-black text-white bg-blue-600 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all transform hover:-translate-y-1 uppercase tracking-widest">
            Se Connecter
        </button>
    </form>
</x-guest-layout>