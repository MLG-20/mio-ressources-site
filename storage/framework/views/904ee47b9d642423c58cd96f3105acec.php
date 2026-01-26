<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Enseignant - MIO</title>
    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('favicon.svg')); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&display=swap'); body { font-family: 'Outfit', sans-serif; } [x-cloak] { display: none !important; }</style>
</head>
<body class="bg-[#f8fafc] text-slate-900" x-data="{ tab: 'bureau' }">

    <!-- NAVBAR -->
    <nav class="bg-slate-900 text-white py-4 px-8 flex justify-between items-center sticky top-0 z-50">
        <a href="/">
           <div class="flex items-center gap-3">
                <?php if (isset($component)) { $__componentOriginal8892e718f3d0d7a916180885c6f012e7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8892e718f3d0d7a916180885c6f012e7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.application-logo','data' => ['class' => 'w-10 h-10']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('application-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-10 h-10']); ?>
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
                <span class="font-black uppercase tracking-tighter">Espace Enseignant</span>
            </div>
        </a>
        <div class="flex items-center gap-4">
            <div class="bg-white/10 px-4 py-2 rounded-2xl flex items-center gap-3">
                <img src="<?php echo e($user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name)); ?>" class="w-8 h-8 rounded-lg object-cover">
                <span class="text-xs font-bold"><?php echo e($user->name); ?></span>
            </div>
            <form method="POST" action="<?php echo e(route('logout')); ?>"> <?php echo csrf_field(); ?>
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-xl font-bold text-xs uppercase shadow-lg">Déconnexion</button>
            </form>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-10 px-6">

        <!-- MENU NAVIGATION PAR ONGRETS -->
        <div class="flex gap-4 mb-10 overflow-x-auto pb-2">
            <button @click="tab = 'bureau'" :class="tab === 'bureau' ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-white text-slate-500'" class="px-8 py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl transition-all whitespace-nowrap">📊 Mon Bureau</button>
            <button @click="tab = 'forum'" :class="tab === 'forum' ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-white text-slate-500'" class="px-8 py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl transition-all whitespace-nowrap">💬 Forum Étudiant</button>
            <button @click="tab = 'settings'" :class="tab === 'settings' ? 'bg-slate-900 text-white' : 'bg-white text-slate-500'" class="px-8 py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl transition-all whitespace-nowrap">⚙️ Paramètres Compte</button>
        </div>

        <!-- 1. ONGLET BUREAU -->
        <div x-show="tab === 'bureau'" x-cloak class="space-y-8 animate-in fade-in duration-500">
            <!-- KPI -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border-b-4 border-blue-600">
                    <span class="text-[10px] font-black text-slate-400 uppercase mb-2 block">Publications</span>
                    <span class="text-4xl font-black text-slate-800"><?php echo e($mesPublications->count()); ?></span>
                </div>
                <div class="bg-slate-900 p-8 rounded-[2.5rem] shadow-xl border-b-4 border-amber-500 text-white">
                    <span class="text-[10px] font-black text-amber-500 uppercase mb-2 block">Mon Solde</span>
                    <span class="text-3xl font-black"><?php echo e(number_format($totalRevenus, 0, ',', ' ')); ?> F</span>
                </div>
                <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border-b-4 border-purple-600">
                    <span class="text-[10px] font-black text-slate-400 uppercase mb-2 block">Spécialité</span>
                    <span class="text-sm font-black text-purple-600 uppercase"><?php echo e($user->specialty ?? 'À renseigner'); ?></span>
                </div>
            </div>

            <!-- Publier -->
            <div class="bg-white rounded-[3rem] p-10 shadow-xl border border-slate-100">
                <h3 class="text-xl font-black text-slate-800 uppercase mb-8">Nouvel Ouvrage</h3>
                <form action="<?php echo e(route('teacher.publication.store')); ?>" method="POST" enctype="multipart/form-data" x-data="{ isPremium: false }" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php echo csrf_field(); ?>
                    <input type="text" name="titre" placeholder="Titre" class="md:col-span-2 w-full bg-slate-50 border-0 rounded-2xl p-4 font-bold" required>
                    <select name="type" class="bg-slate-50 border-0 rounded-2xl p-4 font-bold">
                        <option value="Livre">📚 Livre</option>
                        <option value="Mémoire">🎓 Mémoire</option>
                    </select>
                    <div class="flex items-center justify-between bg-slate-50 p-4 rounded-2xl">
                        <label class="flex items-center gap-2"><input type="checkbox" name="is_premium" x-model="isPremium"> <span class="text-xs font-bold uppercase">Vendre</span></label>
                        <input type="number" name="price" x-show="isPremium" placeholder="Prix CFA" class="w-24 p-2 rounded-xl border-0 font-bold">
                    </div>
                    <textarea name="description" placeholder="Résumé..." class="md:col-span-2 w-full bg-slate-50 border-0 rounded-2xl p-4"></textarea>
                    <input type="file" name="file_path" class="text-xs" required>
                    <input type="file" name="cover_image" class="text-xs">
                    <button type="submit" class="md:col-span-2 bg-blue-600 text-white py-5 rounded-[2rem] font-black shadow-xl uppercase">Publier</button>
                </form>
            </div>

            <!-- Historique des Publications -->
            <div class="bg-white rounded-[3rem] p-10 shadow-xl border border-slate-100">
                <h3 class="text-xl font-black text-slate-800 uppercase mb-8">📚 Historique de vos publications</h3>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($mesPublications->count() > 0): ?>
                    <div class="space-y-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $mesPublications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-6 bg-slate-50 rounded-3xl flex justify-between items-start group hover:bg-blue-50 transition border-l-4 border-blue-600">
                                <div class="flex-1">
                                    <p class="font-black text-slate-800 text-lg"><?php echo e($pub->titre); ?></p>
                                    <div class="flex flex-wrap gap-3 mt-3">
                                        <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pub->type === 'Livre'): ?> 📚 Livre <?php elseif($pub->type === 'Mémoire'): ?> 🎓 Mémoire <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pub->is_premium): ?>
                                            <span class="inline-block bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase">💰 Premium - <?php echo e(number_format($pub->price, 0, ',', ' ')); ?> F</span>
                                        <?php else: ?>
                                            <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase">✓ Gratuit</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <p class="text-[10px] text-slate-400 uppercase font-bold mt-3">Publié le <?php echo e($pub->created_at->format('d/m/Y à H:i')); ?></p>
                                </div>
                                <div class="flex gap-2 ml-4">
                                    <form action="<?php echo e(route('teacher.publication.destroy', $pub->id)); ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publication ?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="bg-red-500 text-white px-6 py-3 rounded-2xl font-black text-xs shadow-lg hover:bg-red-600 transition uppercase">🗑️ Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <p class="text-slate-400 font-bold text-lg">Aucune publication pour le moment</p>
                        <p class="text-slate-300 text-sm mt-2">Publiez votre premier ouvrage dans la section ci-dessus</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <!-- 2. ONGLET FORUM -->
        <div x-show="tab === 'forum'" x-cloak class="bg-white rounded-[3rem] p-10 shadow-xl animate-in slide-in-from-bottom-4 duration-500">
            <h3 class="text-xl font-black text-slate-800 uppercase mb-8">Interagir avec les étudiants</h3>
            <div class="space-y-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $recentSujets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sujet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-6 bg-slate-50 rounded-3xl flex justify-between items-center group hover:bg-blue-50 transition">
                        <div>
                            <p class="font-black text-slate-800"><?php echo e($sujet->titre); ?></p>
                            <p class="text-[10px] text-slate-400 uppercase font-bold mt-1"><?php echo e($sujet->user->name); ?> • <?php echo e($sujet->category->nom); ?></p>
                        </div>
                        <a href="<?php echo e(route('forum.sujet', $sujet->id)); ?>" class="bg-white text-blue-600 px-6 py-2 rounded-full font-black text-xs shadow-sm group-hover:bg-blue-600 group-hover:text-white transition">RÉPONDRE</a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <!-- 3. ONGLET PARAMÈTRES (SÉCURITÉ & PROFIL) -->
        <div x-show="tab === 'settings'" x-cloak class="max-w-2xl mx-auto space-y-8 animate-in zoom-in duration-500">
            <div class="bg-white rounded-[3rem] p-10 shadow-xl">
                <h3 class="text-xl font-black text-slate-800 uppercase mb-8 text-center">Réglages du compte</h3>
                <form action="<?php echo e(route('teacher.profile.update')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Ma Spécialité</label>
                        <input type="text" name="specialty" value="<?php echo e($user->specialty); ?>" class="w-full bg-slate-50 border-0 rounded-2xl p-4 font-black text-blue-600">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Nom Complet</label>
                        <input type="text" name="name" value="<?php echo e($user->name); ?>" class="w-full bg-slate-50 border-0 rounded-2xl p-4 font-bold">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Email</label>
                        <input type="email" name="email" value="<?php echo e($user->email); ?>" class="w-full bg-slate-50 border-0 rounded-2xl p-4 font-bold">
                    </div>

                    <div class="p-6 bg-red-50 rounded-3xl border border-red-100">
                        <p class="text-[10px] font-black text-red-500 uppercase mb-4 tracking-widest">Zone de sécurité</p>
                        <input type="password" name="current_password" placeholder="Mot de passe actuel" class="w-full bg-white border-0 rounded-xl p-4 mb-3 text-sm">
                        <input type="password" name="new_password" placeholder="Nouveau mot de passe" class="w-full bg-white border-0 rounded-xl p-4 mb-3 text-sm">
                        <input type="password" name="new_password_confirmation" placeholder="Confirmer le nouveau" class="w-full bg-white border-0 rounded-xl p-4 text-sm">
                    </div>

                    <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-[2rem] font-black shadow-xl hover:bg-blue-600 transition-all">ENREGISTRER LES MODIFICATIONS</button>
                </form>
            </div>

            <!-- SUPPRESSION COMPTE -->
            <div class="bg-red-50 p-8 rounded-[2rem] border border-red-100 text-center">
                <button onclick="document.getElementById('modal-del').classList.remove('hidden')" class="text-red-500 font-black text-xs uppercase tracking-widest hover:underline">Supprimer mon compte définitivement</button>
            </div>
        </div>
    </main>

    <!-- MODAL SUPPRESSION -->
    <div id="modal-del" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-6">
        <div class="bg-white w-full max-w-md rounded-[3rem] p-10 text-center shadow-2xl">
            <h3 class="text-2xl font-black text-slate-900 mb-4 uppercase">Adieu ?</h3>
            <p class="text-slate-500 mb-8">Cette action est irréversible. Toutes vos publications seront supprimées.</p>
            <form action="<?php echo e(route('teacher.account.delete')); ?>" method="POST">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <input type="password" name="password" placeholder="Mot de passe" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 mb-6 text-center font-bold" required>
                <div class="flex gap-4">
                    <button type="button" onclick="document.getElementById('modal-del').classList.add('hidden')" class="flex-1 bg-slate-100 py-4 rounded-2xl font-bold">ANNULER</button>
                    <button type="submit" class="flex-1 bg-red-600 text-white py-4 rounded-2xl font-bold">SUPPRIMER</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
<?php /**PATH C:\Users\Mamad\Documents\Dossier Mio-Ressource\mio_ressources\resources\views/teacher/dashboard.blade.php ENDPATH**/ ?>