<x-guest-layout>
    
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-900 tracking-tighter uppercase mb-2">Nouveau mot de passe</h1>
        <p class="text-slate-500 font-medium">Sécurisez votre compte en choisissant un mot de passe fort.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-black uppercase text-slate-600 mb-2 ml-1 tracking-wider">Adresse Email</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-slate-400 group-focus-within:text-blue-600 text-lg transition-colors"></i>
                </div>
                <input id="email" class="pl-12 block w-full bg-white border-2 border-slate-300 rounded-xl text-slate-900 font-bold text-lg py-4 shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-600 transition-all placeholder:text-slate-400" 
                       type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm font-bold text-red-600" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-black uppercase text-slate-600 mb-2 ml-1 tracking-wider">Nouveau mot de passe</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-slate-400 group-focus-within:text-blue-600 text-lg transition-colors"></i>
                </div>
                <input id="password" class="pl-12 block w-full bg-white border-2 border-slate-300 rounded-xl text-slate-900 font-bold text-lg py-4 shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-600 transition-all placeholder:text-slate-400" 
                       type="password" name="password" required autocomplete="new-password" placeholder="Minimum 8 caractères">
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
                <input id="password_confirmation" class="pl-12 block w-full bg-white border-2 border-slate-300 rounded-xl text-slate-900 font-bold text-lg py-4 shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-600 transition-all placeholder:text-slate-400"
                       type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Répétez le mot de passe">
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm font-bold text-red-600" />
        </div>

        <button type="submit" class="w-full mt-4 flex justify-center py-4 px-6 border border-transparent rounded-xl shadow-lg text-lg font-black text-white bg-blue-600 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all transform hover:-translate-y-1 uppercase tracking-widest">
            Sauvegarder le mot de passe
        </button>
    </form>
</x-guest-layout>