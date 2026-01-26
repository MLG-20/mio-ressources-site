<x-guest-layout>
    
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-900 tracking-tighter uppercase mb-2">Rejoindre MIO RESSOURCES</h1>
        <p class="text-slate-600 font-bold text-base">
            Déjà membre ?
            <a href="{{ route('login') }}" class="text-blue-700 hover:text-blue-900 underline decoration-2 underline-offset-4 transition">
                Connectez-vous ici
            </a>
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Nom -->
        <div>
            <label for="name" class="block text-xs font-black uppercase text-slate-600 mb-2 ml-1 tracking-wider">Nom complet</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-user text-slate-400 group-focus-within:text-blue-600 text-lg transition-colors"></i>
                </div>
                <input id="name" name="name" type="text" required autofocus autocomplete="name"
                       class="pl-12 block w-full bg-white border-2 border-slate-300 rounded-xl text-slate-900 font-bold text-lg py-4 shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-600 transition-all placeholder:text-slate-400" 
                       placeholder="Prénom Nom">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm font-bold text-red-600" />
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-xs font-black uppercase text-slate-600 mb-2 ml-1 tracking-wider">Adresse Email</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-slate-400 group-focus-within:text-blue-600 text-lg transition-colors"></i>
                </div>
                <input id="email" name="email" type="email" required autocomplete="username"
                       class="pl-12 block w-full bg-white border-2 border-slate-300 rounded-xl text-slate-900 font-bold text-lg py-4 shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-600 transition-all placeholder:text-slate-400" 
                       placeholder="etudiant@uidt.sn">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm font-bold text-red-600" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-black uppercase text-slate-600 mb-2 ml-1 tracking-wider">Mot de passe</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-slate-400 group-focus-within:text-blue-600 text-lg transition-colors"></i>
                </div>
                <input id="password" name="password" type="password" required autocomplete="new-password"
                       class="pl-12 block w-full bg-white border-2 border-slate-300 rounded-xl text-slate-900 font-bold text-lg py-4 shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-600 transition-all placeholder:text-slate-400" 
                       placeholder="Au moins 8 caractères">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm font-bold text-red-600" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-black uppercase text-slate-600 mb-2 ml-1 tracking-wider">Confirmer le mot de passe</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-check-circle text-slate-400 group-focus-within:text-blue-600 text-lg transition-colors"></i>
                </div>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                       class="pl-12 block w-full bg-white border-2 border-slate-300 rounded-xl text-slate-900 font-bold text-lg py-4 shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-600 transition-all placeholder:text-slate-400"
                       placeholder="Répétez le mot de passe">
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm font-bold text-red-600" />
        </div>

        <!-- Choix du type de compte -->
<div class="mt-4" x-data="{ type: 'student' }">
    <label class="block text-xs font-black uppercase text-slate-600 mb-2 tracking-wider">Vous êtes :</label>
    <select name="user_type" x-model="type" class="w-full bg-white border-2 border-slate-300 rounded-xl font-bold p-4 focus:border-blue-600">
        <option value="student">Étudiant</option>
        <option value="teacher">Professeur / Chercheur</option>
    </select>

    <!-- Si Étudiant, on affiche le niveau -->
    <div x-show="type === 'student'" class="mt-4 animate-in fade-in duration-500">
        <label class="block text-xs font-black uppercase text-slate-600 mb-2 tracking-wider">Votre Niveau :</label>
        <select name="student_level" class="w-full bg-white border-2 border-slate-300 rounded-xl font-bold p-4 focus:border-blue-600">
            <option value="L1">Licence 1</option>
            <option value="L2">Licence 2</option>
            <option value="L3">Licence 3</option>
        </select>
    </div>
</div>

        <div>
            <button type="submit" class="w-full mt-4 flex justify-center py-4 px-6 border border-transparent rounded-xl shadow-lg text-lg font-black text-white bg-blue-600 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all transform hover:-translate-y-1 uppercase tracking-widest">
                Créer mon compte
            </button>
        </div>
    </form>
</x-guest-layout>