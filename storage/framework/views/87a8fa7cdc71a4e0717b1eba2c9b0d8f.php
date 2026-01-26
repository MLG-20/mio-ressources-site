<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum MIO - Communauté</title>
    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('favicon.svg')); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-900">

    <nav class="bg-white border-b py-4 px-8 flex justify-between items-center sticky top-0 z-50">
        <a href="/" class="flex items-center gap-2">
            <div class="bg-blue-600 text-white px-2 py-1 rounded font-bold text-sm">MIO</div>
            <span class="font-black text-slate-800">FORUM</span>
        </a>
        <div class="flex items-center gap-4">
            <span class="text-sm font-medium text-slate-500">Bonjour, <?php echo e(Auth::user()->name); ?></span>
            <form method="POST" action="<?php echo e(route('logout')); ?>"><?php echo csrf_field(); ?>
                <button type="submit" class="text-red-500 text-sm font-bold hover:underline">Déconnexion</button>
            </form>
        </div>
    </nav>

    <header class="bg-slate-900 py-12 px-6 text-center">
        <h1 class="text-3xl md:text-5xl font-black text-white tracking-tighter uppercase">Espace Communautaire</h1>
        <p class="text-slate-400 mt-2">Échangez avec les autres étudiants de l'université</p>
    </header>

    <main class="max-w-4xl mx-auto py-12 px-6">
        <div class="grid gap-6">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('forum.category', $cat->id)); ?>" class="group bg-white p-6 rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:border-blue-500 transition-all flex items-center justify-between">
                    <div class="flex items-center gap-6">
                        <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all">
                            <i class="fas fa-comments text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-800"><?php echo e($cat->nom); ?></h3>
                            <p class="text-slate-500 text-sm"><?php echo e($cat->description); ?></p>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-2 rounded-2xl text-center min-w-[80px]">
                        <span class="block font-black text-slate-800"><?php echo e($cat->sujets_count); ?></span>
                        <span class="text-[10px] uppercase font-bold text-slate-400">Sujets</span>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </main>
</body>
</html>
<?php /**PATH C:\Users\Mamad\Documents\Dossier Mio-Ressource\mio_ressources\resources\views/forum/index.blade.php ENDPATH**/ ?>