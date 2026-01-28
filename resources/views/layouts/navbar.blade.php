<nav class="bg-slate-900 text-white py-4 px-4 md:px-8 flex justify-between items-center sticky top-0 z-50 shadow-lg">
    <a href="/">
        <div class="flex items-center gap-2 md:gap-3">
            <x-application-logo class="w-8 md:w-10 h-8 md:h-10" />
            <span class="font-black uppercase tracking-tighter text-xs md:text-base">MIO Ressource</span>
        </div>
    </a>
    <div class="flex items-center gap-2 md:gap-4">
        @auth
            @if(Auth::user()->role === 'professeur')
                <a href="{{ route('teacher.dashboard') }}" class="bg-white/10 px-3 md:px-4 py-2 rounded-xl text-xs font-bold uppercase hover:bg-white/20 transition">
                    Tableau de bord
                </a>
            @else
                <a href="{{ route('user.dashboard') }}" class="bg-white/10 px-3 md:px-4 py-2 rounded-xl text-xs font-bold uppercase hover:bg-white/20 transition">
                    Mon Espace
                </a>
            @endif
        @else
            <a href="{{ route('login') }}" class="text-xs font-bold uppercase hover:text-blue-400 transition">Connexion</a>
            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-xl font-bold text-xs uppercase shadow-lg hover:bg-blue-700 transition">
                S'inscrire
            </a>
        @endauth
    </div>
</nav>
