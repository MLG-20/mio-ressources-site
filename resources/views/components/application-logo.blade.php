<div {{ $attributes->merge(['class' => 'relative group cursor-pointer']) }}>
    <div class="absolute inset-0 rounded-full bg-blue-500/20 blur-2xl group-hover:bg-blue-500/40 transition-all duration-700 animate-pulse"></div>

    <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="w-full h-full drop-shadow-[0_0_15px_rgba(37,99,235,0.3)] overflow-visible relative z-10">

        <defs>
            <filter id="glow" x="-20%" y="-20%" width="140%" height="140%">
                <feGaussianBlur stdDeviation="3" result="blur" />
                <feComposite in="SourceGraphic" in2="blur" operator="over" />
            </filter>
            <path id="topTextPath" d="M 30,100 A 70,70 0 1,1 170,100" />
            <path id="bottomTextPath" d="M 30,100 A 70,70 0 0,0 170,100" />
        </defs>

        <!-- Anneau extérieur décoratif -->
        <circle cx="100" cy="100" r="96" fill="none" stroke="#3b82f6" stroke-width="1.5" stroke-dasharray="12 6" style="opacity:0.3;" />

        <!-- Structure de base -->
        <circle cx="100" cy="100" r="90" fill="white" />
        <circle cx="100" cy="100" r="84" fill="#0f172a" />

        <!-- Cercle central bleu -->
        <circle cx="100" cy="100" r="62" fill="#2563eb" />

        <!-- Texte circulaire haut -->
        <text style="font-size:12px; font-weight:900; fill:white; letter-spacing:0.15em;">
            <textPath href="#topTextPath" startOffset="50%" text-anchor="middle">
                L.M.I.O
            </textPath>
        </text>

        <!-- Texte central MIO -->
        <text x="100" y="118" text-anchor="middle"
              style="fill:white; font-size:48px; font-weight:900; font-family:'Outfit',sans-serif; filter:url(#glow);">
            MIO
        </text>


        <!-- Chapeau de diplômé -->
        <g style="opacity:0.4;">
            <path d="M90 60 L100 52 L110 60 L100 68 Z" fill="white" />
            <path d="M94 62 V68 Q100 72 106 68 V62" fill="none" stroke="white" stroke-width="1" />
        </g>
    </svg>
</div>
