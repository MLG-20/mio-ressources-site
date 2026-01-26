<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliothèque Numérique - MIO</title>
    
    <!-- Scripts & Polices -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('favicon.svg')); ?>">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&display=swap');
        body { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* Personnalisation du scroll à droite */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #2563eb; border-radius: 10px; }
        
        /* Style pour les images de l'éditeur riche */
        .prose img { border-radius: 1.5rem; margin: 2rem 0; box-shadow: 0 20px 50px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900" 
      x-data="{ scrolled: false }" 
      @scroll.window="scrolled = (window.pageYOffset > 200)">

    <!-- BOUTON RETOUR EN HAUT (LE POINTEUR) -->
    <button @click="window.scrollTo({top: 0, behavior: 'smooth'})"
            x-show="scrolled" x-cloak
            x-transition:enter="transition scale-0 rotate-180 duration-300"
            x-transition:enter-end="scale-100 rotate-0"
            class="fixed bottom-8 right-8 z-[90] bg-blue-600 text-white w-14 h-14 rounded-2xl shadow-2xl flex items-center justify-center hover:bg-blue-700 transition-all transform hover:-translate-y-2 group border-4 border-white/20">
        <i class="fas fa-arrow-up text-xl group-hover:animate-bounce"></i>
    </button>

    <!-- NAVBAR MINI -->
    <nav class="bg-white/80 backdrop-blur-md border-b py-4 px-8 flex justify-between items-center sticky top-0 z-50">
        <a href="/" class="flex items-center gap-2 group">
            <div class="bg-blue-600 text-white px-2 py-1 rounded-lg font-bold transition-transform group-hover:-rotate-6">MIO</div>
            <span class="font-black text-slate-800 tracking-tight uppercase">Bibliothèque</span>
        </a>
        <a href="/" class="text-slate-500 hover:text-blue-600 font-bold transition flex items-center gap-2 text-sm uppercase tracking-widest">
            <i class="fas fa-home"></i> Accueil
        </a>
    </nav>

    <header class="py-24 bg-slate-900 text-white text-center relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-indigo-600/10 rounded-full blur-3xl"></div>
        
        <h1 class="relative z-10 text-4xl md:text-7xl font-black tracking-tighter uppercase leading-none mb-4">Savoir & Recherche</h1>
        <p class="relative z-10 text-blue-400 font-bold tracking-[0.3em] uppercase text-xs">Accès aux mémoires et ouvrages de référence</p>
    </header>

    <main class="max-w-7xl mx-auto py-20 px-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $publications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <!-- Chaque document gère sa propre fenêtre de description -->
                <div x-data="{ showDesc: false }" class="h-full">
                    
                    <div class="group bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 overflow-hidden border border-slate-100 transition-all duration-500 hover:-translate-y-3 flex flex-col h-full">
                        
                        <!-- Couverture -->
                        <div class="h-72 relative bg-slate-100 overflow-hidden">
                            <img src="<?php echo e($pub->cover_image ? asset('storage/'.$pub->cover_image) : 'https://placehold.co/400x600/e2e8f0/1e293b?text=Livre'); ?>" 
                                 class="w-full h-full object-cover transition transform duration-1000 group-hover:scale-110">
                            
                            <div class="absolute top-4 left-4">
                                <span class="bg-slate-900/80 backdrop-blur text-white px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border border-white/10"><?php echo e($pub->type); ?></span>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pub->is_premium): ?>
                                <div class="absolute bottom-4 right-4 bg-amber-400 text-slate-900 px-3 py-1 rounded-full text-[10px] font-black uppercase shadow-lg"><?php echo e($pub->price); ?> CFA</div>
                            <?php else: ?>
                                <div class="absolute bottom-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-[10px] font-black uppercase shadow-lg">Gratuit</div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <!-- Contenu de la Carte -->
                        <div class="p-8 flex-1 flex flex-col">
                            <h3 class="font-black text-slate-800 text-xl leading-tight mb-4 group-hover:text-blue-600 transition-colors line-clamp-2">
                                <?php echo e($pub->titre); ?>

                            </h3>

                            <!-- BOUTON POUR OUVRIR LE POPUP -->
                            <button @click="showDesc = true" class="text-blue-600 text-xs font-black uppercase tracking-widest flex items-center gap-2 hover:gap-3 transition-all mb-6">
                                <i class="fas fa-align-left"></i> Lire le résumé
                            </button>
                            
                            <div class="flex items-center gap-3 mb-6 mt-auto pt-6 border-t border-slate-50">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-xs overflow-hidden border border-slate-200 shadow-inner">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pub->user->avatar): ?>
                                        <img src="<?php echo e(asset('storage/'.$pub->user->avatar)); ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <i class="fas fa-user-tie"></i>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-700 truncate max-w-[120px]"><?php echo e($pub->user->name); ?></span>
                                    <span class="text-[8px] font-black text-blue-500 uppercase tracking-widest"><?php echo e($pub->user->specialty ?? 'Enseignant'); ?></span>
                                </div>
                            </div>

                           <!-- SYSTEME DE NOTATION HYBRIDE -->
<div class="mb-6">
    <!-- 1. Affichage Public (Pour tout le monde) -->
    <div class="flex items-center gap-2 mb-2">
        <div class="flex text-yellow-400 text-xs">
            <?php $avg = round($pub->ratings_avg_stars ?? 0); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i=1; $i<=5; $i++): ?>
                <i class="fas fa-star <?php echo e($i <= $avg ? '' : 'text-slate-200'); ?>"></i>
            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <span class="text-[10px] font-bold text-slate-400">(<?php echo e($pub->ratings_count); ?> avis)</span>
    </div>

    <!-- 2. Formulaire de Vote (Seulement pour les membres) -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
        <?php if (isset($component)) { $__componentOriginal9d764c9e0c4d8c20ad151d87d49b2add = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9d764c9e0c4d8c20ad151d87d49b2add = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rate-item','data' => ['itemId' => $pub->id,'type' => 'publication']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rate-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['itemId' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pub->id),'type' => 'publication']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9d764c9e0c4d8c20ad151d87d49b2add)): ?>
<?php $attributes = $__attributesOriginal9d764c9e0c4d8c20ad151d87d49b2add; ?>
<?php unset($__attributesOriginal9d764c9e0c4d8c20ad151d87d49b2add); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9d764c9e0c4d8c20ad151d87d49b2add)): ?>
<?php $component = $__componentOriginal9d764c9e0c4d8c20ad151d87d49b2add; ?>
<?php unset($__componentOriginal9d764c9e0c4d8c20ad151d87d49b2add); ?>
<?php endif; ?>
    <?php else: ?>
        <p class="text-[9px] text-slate-400 italic">
            <a href="<?php echo e(route('login')); ?>" class="text-blue-500 hover:underline">Connectez-vous</a> pour donner votre avis.
        </p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

                            <!-- Bouton Action -->
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pub->is_premium): ?>
                                <a href="<?php echo e(route('book.checkout', $pub->id)); ?>" class="block w-full bg-slate-900 text-white text-center py-4 rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-blue-600 transition-all shadow-lg active:scale-95">
                                    Acheter l'ouvrage
                                </a>
                            <?php else: ?>
                                <a href="<?php echo e(asset('storage/'.$pub->file_path)); ?>" target="_blank" class="block w-full bg-blue-50 text-blue-600 text-center py-4 rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-blue-600 hover:text-white transition-all active:scale-95">
                                    Lire maintenant
                                </a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <!-- LE POPUP (MODAL) DE DESCRIPTION -->
                    <div x-show="showDesc" x-cloak 
                         class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/80 backdrop-blur-md"
                         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                        
                        <div @click.away="showDesc = false" 
                             class="bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
                            
                            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                                <h2 class="text-2xl font-black text-slate-800 tracking-tighter uppercase">À propos de l'ouvrage</h2>
                                <button @click="showDesc = false" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-red-500 transition-all shadow-sm">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <div class="p-10 max-h-[60vh] overflow-y-auto">
                                <div class="flex flex-col md:flex-row items-center gap-8 mb-8 text-center md:text-left">
                                    <img src="<?php echo e($pub->cover_image ? asset('storage/'.$pub->cover_image) : 'https://placehold.co/200x300'); ?>" 
                                         class="w-32 h-44 object-cover rounded-2xl shadow-2xl border-4 border-slate-50">
                                    <div>
                                        <h3 class="text-2xl font-black text-slate-800 leading-tight mb-2"><?php echo e($pub->titre); ?></h3>
                                        <p class="text-blue-600 font-bold text-sm uppercase"><?php echo e($pub->user->name); ?> &bull; <?php echo e($pub->type); ?></p>
                                    </div>
                                </div>
                                <div class="h-px w-full bg-slate-100 mb-8"></div>
                                <div class="text-slate-600 text-lg leading-relaxed font-medium">
                                    <?php echo nl2br(e($pub->description)); ?>

                                </div>
                            </div>

                            <div class="p-8 bg-slate-50 border-t border-slate-100 flex justify-end">
                                <button @click="showDesc = false" class="px-10 py-4 bg-slate-900 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-blue-600 transition-all">
                                    Fermer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full text-center py-32 bg-white rounded-[3rem] border border-dashed border-slate-200">
                    <i class="fas fa-book-reader text-6xl text-slate-200 mb-4"></i>
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-sm">La bibliothèque est en cours de remplissage...</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </main>

    <footer class="bg-white border-t py-16 text-center">
        <p class="text-slate-400 text-xs font-black uppercase tracking-[0.4em]">MIO Ressources &bull; Excellence & Recherche</p>
    </footer>

</body>
</html><?php /**PATH C:\Users\Mamad\Documents\Dossier Mio-Ressource\mio_ressources\resources\views/library/index.blade.php ENDPATH**/ ?>