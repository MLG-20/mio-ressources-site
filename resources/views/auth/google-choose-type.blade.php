<x-guest-layout>

    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-900 tracking-tighter uppercase mb-2">Dernière étape</h1>
        <p class="text-slate-600 font-bold text-base">
            Bienvenue <span class="text-blue-700">{{ $name }}</span> ! Dites-nous qui vous êtes pour finaliser votre compte.
        </p>
    </div>

    <!-- Rappel du compte Google connecté -->
    <div class="mb-6 flex items-center gap-3 bg-slate-50 border-2 border-slate-200 rounded-xl px-4 py-3">
        <i class="fab fa-google text-lg" style="color:#4285F4"></i>
        <span class="font-bold text-slate-700 text-sm break-all">{{ $email }}</span>
    </div>

    <form method="POST" action="{{ route('google.store') }}" class="space-y-5" x-data="{ type: 'student' }">
        @csrf

        <!-- Choix du type de compte -->
        <div>
            <label class="block text-xs font-black uppercase text-slate-600 mb-2 tracking-wider">Vous êtes :</label>
            <select name="user_type" x-model="type" class="w-full bg-white border-2 border-slate-300 rounded-xl font-bold p-4 focus:border-blue-600">
                <option value="student">Étudiant</option>
                <option value="teacher">Professeur / Chercheur</option>
            </select>
            <x-input-error :messages="$errors->get('user_type')" class="mt-2 text-sm font-bold text-red-600" />
        </div>

        <!-- Si Étudiant, on affiche le niveau -->
        <div x-show="type === 'student'" class="animate-in fade-in duration-500">
            <label class="block text-xs font-black uppercase text-slate-600 mb-2 tracking-wider">Votre Niveau :</label>
            <select name="student_level" :required="type === 'student'" class="w-full bg-white border-2 border-slate-300 rounded-xl font-bold p-4 focus:border-blue-600">
                <option value="" disabled selected>— Choisis ton niveau —</option>
                <option value="L1">Licence 1</option>
                <option value="L2">Licence 2</option>
                <option value="L3">Licence 3</option>
            </select>
            <x-input-error :messages="$errors->get('student_level')" class="mt-2 text-sm font-bold text-red-600" />
        </div>

        <div>
            <button type="submit" class="w-full mt-4 flex justify-center py-4 px-6 border border-transparent rounded-xl shadow-lg text-lg font-black text-white bg-blue-600 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all transform hover:-translate-y-1 uppercase tracking-widest">
                Finaliser mon compte
            </button>
        </div>
    </form>
</x-guest-layout>
