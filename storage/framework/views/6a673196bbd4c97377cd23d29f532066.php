<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($matiere->nom); ?> - MIO Ressources</title>
    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('favicon.svg')); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f8fafc] text-slate-900 font-sans">

    <!-- NAVBAR MINI -->
    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 py-4 px-8 flex justify-between items-center sticky top-0 z-50">
        <a href="/" class="flex items-center gap-2 group">
            <div class="bg-blue-600 text-white px-2 py-1 rounded-lg font-bold transition-transform group-hover:-rotate-6">MIO</div>
            <span class="font-black text-slate-800 tracking-tight">RESSOURCES</span>
        </a>
        <a href="<?php echo e(route('semestre.show', $matiere->semestre_id)); ?>" class="text-slate-500 hover:text-blue-600 font-bold transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            <span class="hidden sm:inline">Retour au <?php echo e($matiere->semestre->nom); ?></span>
        </a>
    </nav>

    <!-- HEADER MATIÈRE -->
    <header class="py-16 bg-white border-b border-slate-200">
        <div class="max-w-5xl mx-auto px-6 text-center md:text-left">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                <div>
                    <span class="bg-blue-100 text-blue-700 px-4 py-1 rounded-full text-xs font-black uppercase tracking-widest"><?php echo e($matiere->code); ?></span>
                    <h1 class="text-4xl md:text-6xl font-black text-slate-900 mt-4 tracking-tighter"><?php echo e($matiere->nom); ?></h1>
                    <p class="text-slate-500 mt-4 text-lg font-medium">Bibliothèque des supports pédagogiques officiels</p>
                </div>
                <div class="bg-slate-50 p-4 rounded-3xl border border-slate-100 hidden lg:block">
                    <span class="block text-2xl font-black text-blue-600"><?php echo e($matiere->ressources->count()); ?></span>
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-widest">Documents</span>
                </div>
            </div>
        </div>
    </header>

    <main class="py-12 max-w-5xl mx-auto px-6">

        <h2 class="text-sm font-black text-slate-400 uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
            <i class="fas fa-folder-open text-blue-500"></i> Ressources disponibles
        </h2>

        <div class="grid gap-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $matiere->ressources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ressource): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="group bg-white p-5 rounded-3xl border border-slate-200 shadow-sm flex flex-col sm:flex-row justify-between items-center hover:shadow-xl hover:border-blue-400 transition-all duration-300">
                    <div class="flex items-center gap-5 w-full sm:w-auto">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-colors duration-300 <?php echo e($ressource->type == 'Vidéo' ? 'bg-red-50 text-red-600 group-hover:bg-red-600' : 'bg-blue-50 text-blue-600 group-hover:bg-blue-600'); ?> group-hover:text-white">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ressource->type == 'Vidéo'): ?>
                                <i class="fas fa-play text-xl"></i>
                            <?php elseif($ressource->type == 'Cours'): ?>
                                <i class="fas fa-book text-xl"></i>
                            <?php else: ?>
                                <i class="fas fa-file-lines text-xl"></i>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div>
                            <h3 class="font-black text-slate-800 text-lg group-hover:text-blue-600 transition-colors"><?php echo e($ressource->titre); ?></h3>

                            <div class="flex items-center gap-3 mt-1">
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400"><?php echo e($ressource->type); ?></span>
                                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>

                                <!-- 1. INSERTION DU BADGE PREMIUM / GRATUIT ICI -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ressource->is_premium): ?>
                                    <div class="flex items-center gap-2">
                                        <span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-widest border border-amber-200 flex items-center gap-1">
                                            <i class="fas fa-star text-[8px]"></i> Premium
                                        </span>
                                        <span class="text-slate-900 font-black text-xs"><?php echo e(number_format($ressource->price, 0, ',', ' ')); ?> CFA</span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-emerald-500 font-bold text-[10px] uppercase tracking-tight flex items-center gap-1">
                                        <i class="fas fa-check-circle text-[8px]"></i> Gratuit
                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 sm:mt-0 w-full sm:w-auto">
                        <!-- 2. INSERTION DE LA LOGIQUE DE BOUTON (OUVRIR vs ACHETER) -->
                        <?php
                            $hasPurchased = false;
                            if(auth()->check() && $ressource->is_premium) {
                                // On vérifie si l'achat existe dans la table
                                $hasPurchased = \App\Models\Purchase::where('user_id', auth()->id())
                                                ->where('ressource_id', $ressource->id)
                                                ->exists();
                            }
                        ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$ressource->is_premium || $hasPurchased): ?>
                            <!-- BOUTON ACCÈS LIBRE -->
                            <a href="<?php echo e(auth()->check() ? route('ressource.download', $ressource->id) : (asset('storage/' . $ressource->file_path))); ?>"
                               target="_blank"
                               class="flex items-center justify-center gap-2 bg-slate-900 text-white px-8 py-3 rounded-2xl font-bold hover:bg-blue-600 transition-all shadow-lg shadow-slate-200 w-full min-w-[160px]">
                                <i class="fas <?php echo e($ressource->type == 'Vidéo' ? 'fa-play-circle' : 'fa-cloud-download-alt'); ?>"></i>
                                <?php echo e($ressource->type == 'Vidéo' ? 'Regarder' : 'Ouvrir'); ?>

                            </a>
                        <?php else: ?>
                            <!-- BOUTON ACHAT -->
                            <a href="<?php echo e(route('payment.checkout', $ressource->id)); ?>"
                                class="flex items-center justify-center gap-2 bg-amber-500 text-white px-8 py-3 rounded-2xl font-bold hover:bg-amber-600 transition-all shadow-lg shadow-amber-200">
                                <i class="fas fa-shopping-cart"></i> Débloquer (<?php echo e($ressource->price); ?> CFA)
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ressource->is_premium): ?>
                        <div class="mt-2 px-5 pb-5">
                            <?php if (isset($component)) { $__componentOriginal9d764c9e0c4d8c20ad151d87d49b2add = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9d764c9e0c4d8c20ad151d87d49b2add = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rate-item','data' => ['itemId' => $ressource->id,'type' => 'ressource']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rate-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['itemId' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($ressource->id),'type' => 'ressource']); ?>
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
                        </div>
                     <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-slate-300">
                    <i class="fas fa-box-open text-5xl text-slate-200 mb-4"></i>
                    <p class="text-slate-400 font-bold tracking-tight">Aucun document n'a été publié pour cette matière.</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

    </main>

    <footer class="py-12 text-center">
        <div class="h-px w-20 bg-slate-200 mx-auto mb-8"></div>
        <p class="text-slate-400 text-sm font-medium">
            &copy; <?php echo e(date('Y')); ?> MIO Ressources • Université Iba Der Thiam
        </p>
    </footer>

</body>
</html>
<?php /**PATH C:\Users\Mamad\Documents\Dossier Mio-Ressource\mio_ressources\resources\views/matieres/show.blade.php ENDPATH**/ ?>