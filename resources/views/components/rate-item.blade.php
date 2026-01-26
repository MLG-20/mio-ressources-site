@props(['itemId', 'type'])

<div x-data="{ rating: 5, hoverRating: 0 }" class="bg-slate-50 p-6 rounded-3xl border border-slate-100 mt-6">
    <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-4">Noter ce document</h4>
    
    <form action="{{ route('resource.rate', ['id' => $itemId, 'type' => $type]) }}" method="POST">
        @csrf
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
</div>