<!-- Séparateur "ou" -->
<div class="my-6 flex items-center gap-4">
    <div class="h-px flex-1 bg-slate-200"></div>
    <span class="text-xs font-black uppercase text-slate-400 tracking-wider">ou</span>
    <div class="h-px flex-1 bg-slate-200"></div>
</div>

<!-- Connexion / inscription via Google -->
<a href="{{ route('google.redirect') }}"
   class="w-full flex items-center justify-center gap-3 py-4 px-6 border-2 border-slate-300 rounded-xl bg-white text-slate-700 font-black text-base hover:bg-slate-50 hover:border-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-100 transition-all uppercase tracking-wide">
    <i class="fab fa-google text-lg" style="color:#4285F4"></i>
    Continuer avec Google
</a>
