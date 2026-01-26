<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($semestre->nom); ?> - MIO</title>
    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('favicon.svg')); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-900">

    <nav class="bg-white shadow-sm py-4 px-8 flex justify-between items-center sticky top-0 z-50">
        <a href="/" class="flex items-center gap-2 group">
            <div class="bg-blue-600 text-white px-2 py-1 rounded-lg font-bold">MIO</div>
            <span class="font-black text-gray-800">RESSOURCES</span>
        </a>
        <a href="/" class="text-gray-500 hover:text-blue-600 font-bold transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </nav>

    <header class="relative py-20 bg-blue-900 text-white text-center overflow-hidden">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($semestre->image_path): ?>
            <img src="<?php echo e(asset('storage/' . $semestre->image_path)); ?>" class="absolute inset-0 w-full h-full object-cover opacity-20">
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div class="relative z-10">
            <span class="bg-blue-500/30 backdrop-blur-md border border-blue-400 px-4 py-1 rounded-full text-xs font-black uppercase tracking-widest"><?php echo e($semestre->niveau); ?></span>
            <h1 class="text-4xl md:text-6xl font-black mt-4 uppercase tracking-tighter"><?php echo e($semestre->nom); ?></h1>
            <p class="text-blue-200 mt-2 italic font-medium">Retrouvez tous vos supports de cours et travaux dirigés</p>
        </div>
    </header>

    <main class="py-16 px-6 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $semestre->matieres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $matiere): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <!-- LA CORRECTION EST ICI : href="<?php echo e(route('matiere.show', $matiere->id)); ?>" -->
                <a href="<?php echo e(route('matiere.show', $matiere->id)); ?>"
                   class="group bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-2xl hover:shadow-blue-200/50 hover:border-blue-400 transition-all duration-300 flex flex-col justify-between h-full">

                    <div>
                        <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                            <i class="fas fa-book-open text-xl"></i>
                        </div>
                        <span class="text-[10px] font-black font-mono text-slate-400 uppercase tracking-widest"><?php echo e($matiere->code); ?></span>
                        <h3 class="text-xl font-black text-slate-800 mt-2 leading-tight group-hover:text-blue-600 transition-colors"><?php echo e($matiere->nom); ?></h3>
                    </div>

                    <div class="mt-10 flex justify-between items-center border-t border-slate-50 pt-6">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Supports disponibles</span>
                        <div class="bg-slate-50 text-slate-800 h-10 w-10 rounded-xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </main>

</body>
</html>
<?php /**PATH C:\Users\Mamad\Documents\Dossier Mio-Ressource\mio_ressources\resources\views/semestres/show.blade.php ENDPATH**/ ?>