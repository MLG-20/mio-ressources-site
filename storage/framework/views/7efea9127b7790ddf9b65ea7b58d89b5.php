<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['itemId', 'type']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['itemId', 'type']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div x-data="{ rating: 5, hoverRating: 0 }" class="bg-slate-50 p-6 rounded-3xl border border-slate-100 mt-6">
    <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-4">Noter ce document</h4>
    
    <form action="<?php echo e(route('resource.rate', ['id' => $itemId, 'type' => $type])); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <!-- Système d'étoiles interactif -->
        <div class="flex gap-2 mb-4">
            <template x-for="i in 5">
                <button type="button" @click="rating = i" @mouseenter="hoverRating = i" @mouseleave="hoverRating = 0" class="text-2xl transition-transform hover:scale-125 focus:outline-none">
                    <i class="fas fa-star" :class="(hoverRating || rating) >= i ? 'text-yellow-400' : 'text-slate-200'"></i>
                </button>
            </template>
            <input type="hidden" name="stars" :value="rating">
        </div>

        <textarea name="comment" rows="2" placeholder="Votre avis en quelques mots..." 
                  class="w-full bg-white border-slate-200 rounded-2xl p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none mb-3" required></textarea>
        
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-full font-bold text-xs hover:bg-blue-700 transition shadow-lg">
            PUBLIER MA NOTE
        </button>
    </form>
</div><?php /**PATH C:\Users\Mamad\Documents\Dossier Mio-Ressource\mio_ressources\resources\views/components/rate-item.blade.php ENDPATH**/ ?>