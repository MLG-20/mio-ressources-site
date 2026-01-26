<div <?php echo e($attributes->merge(['class' => 'relative group cursor-pointer'])); ?>>
    <!-- Effet d'Aura Lumineuse en arrière-plan (Pulse) -->
    <div class="absolute inset-0 rounded-full bg-blue-500/20 blur-2xl group-hover:bg-blue-500/40 transition-all duration-700 animate-pulse"></div>

    <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="w-full h-full drop-shadow-[0_0_15px_rgba(37,99,235,0.3)] overflow-visible relative z-10">
        
        <!-- Filtre pour la lueur (Glow) interne -->
        <defs>
            <filter id="glow" x="-20%" y="-20%" width="140%" height="140%">
                <feGaussianBlur stdDeviation="3" result="blur" />
                <feComposite in="SourceGraphic" in2="blur" operator="over" />
            </filter>
            
            <!-- Chemins pour le texte agrandi -->
            <path id="topTextPath" d="M 30,100 A 70,70 0 1,1 170,100" />
            <path id="bottomTextPath" d="M 30,100 A 70,70 0 0,0 170,100" />
        </defs>

        <!-- 1. Anneau extérieur décoratif -->
        <circle cx="100" cy="100" r="96" fill="none" stroke="#3b82f6" stroke-width="1.5" stroke-dasharray="12 6" class="animate-[spin_60s_linear_infinite] opacity-30" />
        
        <!-- 2. Structure de base -->
        <circle cx="100" cy="100" r="90" fill="white" class="shadow-inner" />
        <circle cx="100" cy="100" r="84" fill="#0f172a" /> <!-- Fond bleu nuit pour le texte -->
        
        <!-- 3. Cercle central lumineux -->
        <circle cx="100" cy="100" r="62" fill="#2563eb" class="transition-all duration-500 group-hover:fill-blue-600 shadow-[inset_0_0_20px_rgba(255,255,255,0.2)]" />

        <!-- 4. TEXTE CIRCULAIRE AGRANDI (Haut) -->
        <text class="text-[12px] font-black uppercase fill-white tracking-[0.15em]" style="filter: drop-shadow(0 0 2px rgba(255,255,255,0.5));">
            <textPath href="#topTextPath" startOffset="50%" text-anchor="middle">
                L.M.I.O
            </textPath>
        </text>

        <!-- 5. TEXTE CENTRAL "MIO" GÉANT -->
        <text x="100" y="118" text-anchor="middle" 
              class="fill-white font-black text-[48px] tracking-tighter transition-all duration-500 group-hover:scale-110" 
              style="filter: url(#glow); font-family: 'Outfit', sans-serif;">
            MIO
        </text>

        <!-- 6. TEXTE CIRCULAIRE BAS -->
        <text class="text-[14px] font-black fill-blue-400 tracking-[0.2em]">
            <textPath href="#bottomTextPath" startOffset="50%" text-anchor="middle">
                2023 - 2024
            </textPath>
        </text>

        <!-- 7. Petit chapeau de diplômé brillant -->
        <g class="transition-all duration-500 group-hover:-translate-y-2 origin-center transform opacity-40 group-hover:opacity-100">
            <path d="M90 60 L100 52 L110 60 L100 68 Z" fill="white" />
            <path d="M94 62 V68 Q100 72 106 68 V62" fill="none" stroke="white" stroke-width="1" />
        </g>
    </svg>
</div><?php /**PATH C:\Users\Mamad\Documents\Dossier Mio-Ressource\mio_ressources\resources\views/components/application-logo.blade.php ENDPATH**/ ?>