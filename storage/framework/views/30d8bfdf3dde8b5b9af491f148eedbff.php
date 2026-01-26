<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accès MIO Ressources</title>
    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('favicon.svg')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #2563eb; border-radius: 10px; }
    </style>
</head>
<body class="antialiased bg-white text-slate-900" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 100)">

    <!-- Bouton Remonter -->
    <button @click="window.scrollTo({top: 0, behavior: 'smooth'})" x-show="scrolled" x-cloak
            class="fixed bottom-8 right-8 z-[110] bg-blue-600 text-white w-12 h-12 rounded-2xl shadow-2xl flex items-center justify-center hover:bg-blue-700 transition-all transform hover:-translate-y-1">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Wrapper Principal : items-stretch est la clé -->
    <div class="min-h-screen flex flex-col lg:flex-row w-full items-stretch">

        <!-- CÔTÉ GAUCHE : FORMULAIRE -->
        <div class="w-full lg:w-[45%] flex flex-col bg-white relative z-10 shadow-2xl">

            <!-- Navbar interne -->
            <div class="p-6 md:px-12 flex justify-between items-center">
                <a href="/" class="flex items-center gap-2 group">
                    <?php if (isset($component)) { $__componentOriginal8892e718f3d0d7a916180885c6f012e7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8892e718f3d0d7a916180885c6f012e7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.application-logo','data' => ['class' => 'w-8 h-8']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('application-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-8 h-8']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $attributes = $__attributesOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $component = $__componentOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__componentOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
                    <span class="text-base font-black tracking-tighter text-slate-800 uppercase">MIO <span class="font-light text-slate-400">Ressources</span></span>
                </a>
                <a href="/" class="text-[10px] font-black text-slate-400 hover:text-blue-600 transition uppercase tracking-widest">
                    <i class="fas fa-home mr-1"></i> Accueil
                </a>
            </div>

            <!-- Centre du contenu -->
            <div class="flex-1 flex flex-col justify-center px-6 sm:px-12 md:px-20 py-12">
                <div class="w-full max-w-md mx-auto">
                    <?php echo e($slot); ?>

                </div>
            </div>

            <!-- Footer gauche -->
            <div class="p-6 text-center border-t border-slate-50">
                <p class="text-slate-300 text-[9px] font-bold uppercase tracking-[0.3em]">&copy; <?php echo e(date('Y')); ?> MIO RESSOURCES</p>
            </div>
        </div>

        <!-- CÔTÉ DROIT : IMAGE DYNAMIQUE (Totalement remplie) -->
        <div class="hidden lg:flex relative flex-1 bg-slate-900 overflow-hidden">
            <?php
                $bgImage = $globalSettings['auth_bg_image'] ?? null;
                if ($bgImage && !str_starts_with($bgImage, 'http')) {
                    $bgImage = asset('storage/' . $bgImage);
                }
                $finalBg = $bgImage ?? 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=2070';
            ?>

            <!-- L'image utilise h-full pour s'étirer sur toute la hauteur de la colonne gauche -->
            <img class="absolute inset-0 h-full w-full object-cover opacity-50 transform scale-105"
                 src="<?php echo e($finalBg); ?>" alt="Branding">

            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/90 via-transparent to-black/60"></div>

            <!-- Texte Centré -->
            <div class="relative z-10 w-full px-20 flex flex-col justify-center">
                <div class="h-1.5 w-16 bg-blue-500 mb-8 rounded-full shadow-lg shadow-blue-500/50"></div>

                <h2 class="text-6xl font-black text-white leading-[0.95] tracking-tighter mb-8 uppercase drop-shadow-2xl">
                    <?php echo nl2br(e($globalSettings['auth_title'] ?? 'L\'excellence commence ici.')); ?>

                </h2>

                <p class="text-blue-100 text-xl font-light leading-relaxed italic border-l-4 border-blue-500/30 pl-8">
                    "<?php echo e($globalSettings['auth_description'] ?? 'Simplifier l\'accès au savoir pour bâtir l\'avenir de chaque étudiant.'); ?>"
                </p>

                <div class="flex gap-6 mt-16 opacity-80">
                    <div class="flex flex-col"><span class="text-2xl font-black text-white">47</span><span class="text-[9px] font-bold text-blue-400 uppercase tracking-widest">Matières</span></div>
                    <div class="w-px h-10 bg-white/10"></div>
                    <div class="flex flex-col"><span class="text-2xl font-black text-white">L1-L3</span><span class="text-[9px] font-bold text-blue-400 uppercase tracking-widest">Cursus complet</span></div>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
<?php /**PATH C:\Users\Mamad\Documents\Dossier Mio-Ressource\mio_ressources\resources\views/layouts/guest.blade.php ENDPATH**/ ?>