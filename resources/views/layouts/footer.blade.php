<footer class="bg-slate-950 text-white py-20 px-8 border-t border-white/5">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-16 border-b border-white/5 pb-16">
        <div class="space-y-6 text-center md:text-left">
            <div class="flex items-center justify-center md:justify-start gap-2">
                <x-application-logo class="w-10 h-10" />
                <span class="font-bold text-xl tracking-tighter uppercase tracking-widest">MIO <span class="font-light opacity-50">RESSOURCES</span></span>
            </div>
            <p class="text-slate-500 text-sm leading-relaxed max-w-sm mx-auto md:mx-0">La plateforme collaborative de référence pour les étudiants de l'Université Iba Der Thiam.</p>
        </div>
        <div>
            <h4 class="font-bold text-[10px] uppercase tracking-[0.4em] text-blue-500 mb-8 text-center md:text-left">Contact Officiel</h4>
            <div class="space-y-4 text-slate-400 text-sm text-center md:text-left">
                <p class="flex items-center justify-center md:justify-start gap-3"><i class="fas fa-envelope text-blue-500"></i> {{ $globalSettings['contact_email'] ?? 'contact@mio.sn' }}</p>
                <p class="flex items-center justify-center md:justify-start gap-3"><i class="fas fa-phone text-blue-500"></i> {{ $globalSettings['contact_phone'] ?? '' }}</p>
            </div>
        </div>
        <div class="text-center md:text-left">
            <h4 class="font-bold text-[10px] uppercase tracking-[0.4em] text-blue-500 mb-8">Réseaux Sociaux</h4>
            <div class="flex justify-center md:justify-start gap-5">
                @if(isset($globalSettings['social_linkedin']))
                    <a href="{{ $globalSettings['social_linkedin'] }}" target="_blank" class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center text-white hover:bg-blue-600 transition-all shadow-lg"><i class="fab fa-linkedin-in text-xl"></i></a>
                @endif
                @if(isset($globalSettings['social_github']))
                    <a href="{{ $globalSettings['social_github'] }}" target="_blank" class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center text-white hover:bg-blue-600 transition-all shadow-lg"><i class="fab fa-github text-xl"></i></a>
                @endif
            </div>
        </div>
    </div>
    <div class="text-center mt-12 text-slate-700 text-[10px] font-bold uppercase tracking-[0.5em]">
        &copy; {{ date('Y') }} MIO Ressources &bull; Excellence & Partage
    </div>
</footer>
