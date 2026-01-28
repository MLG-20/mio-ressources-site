{{--
    Composant Modal de Suppression Réutilisable

    Utilisation :
    <x-delete-confirmation-modal
        :id="$item->id"
        title="Supprimer ce cours"
        message="Êtes-vous sûr de vouloir supprimer ce cours ? Cette action est irréversible."
        action-route="teacher.private-lessons.destroy"
        cancel-text="Annuler"
        delete-text="Supprimer définitivement"
        item-type="course"
    />
--}}

@props([
    'id' => '',
    'title' => 'Confirmation de suppression',
    'message' => 'Êtes-vous sûr de vouloir supprimer cet élément ? Cette action est irréversible.',
    'actionRoute' => '',
    'cancelText' => 'Annuler',
    'deleteText' => 'Supprimer',
    'itemType' => 'item',
    'icon' => 'trash',
])

<div
    x-data="{ isOpen: false }"
    x-cloak
    @keydown.escape="isOpen = false"
    class="relative"
>
    <!-- Bouton Déclencheur -->
    <button
        @click="isOpen = true"
        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold uppercase bg-red-600 text-white hover:bg-red-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-105"
    >
        <i class="fas fa-{{ $icon }}"></i>
        <span>Supprimer</span>
    </button>

    <!-- Overlay -->
    <div
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="isOpen = false"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40"
    ></div>

    <!-- Modal -->
    <div
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        @click.stop
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
    >
        <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden border-2 border-red-100">

            <!-- Icône Top -->
            <div class="relative h-24 bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center overflow-hidden">
                <!-- Animated Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-2 left-4 w-20 h-20 bg-white rounded-full blur-2xl"></div>
                    <div class="absolute bottom-2 right-4 w-16 h-16 bg-white rounded-full blur-2xl"></div>
                </div>

                <!-- Icon Container with Pulse -->
                <div class="relative flex items-center justify-center">
                    <div
                        class="absolute w-20 h-20 bg-red-400 rounded-full opacity-20 animate-pulse"
                        style="animation-duration: 2s;"
                    ></div>
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/30">
                        <i class="fas fa-{{ $icon }} text-3xl text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 md:p-8">

                <!-- Title -->
                <h3 class="text-xl md:text-2xl font-black text-slate-900 mb-2 text-center">
                    {{ $title }}
                </h3>

                <!-- Message -->
                <p class="text-sm md:text-base text-slate-600 text-center mb-6 leading-relaxed">
                    {{ $message }}
                </p>

                <!-- Warning Badge -->
                <div class="bg-red-50 rounded-xl p-4 mb-6 border border-red-200 flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-red-600 flex-shrink-0 mt-1"></i>
                    <div>
                        <p class="text-xs font-bold text-red-900 uppercase mb-1">⚠️ Important</p>
                        <p class="text-xs text-red-700">Cette action ne peut pas être annulée. Procédez avec prudence.</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <!-- Delete Button -->
                    <form
                        action="{{ route($actionRoute, $id) }}"
                        method="POST"
                        class="w-full"
                        @submit.prevent="
                            if (confirm('Êtes-vous absolument sûr ? Cette action est irréversible.')) {
                                $el.submit();
                            }
                        "
                    >
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white py-3 md:py-4 rounded-xl md:rounded-2xl font-black text-xs md:text-sm uppercase shadow-lg hover:shadow-xl transition-all transform hover:scale-105 flex items-center justify-center gap-2"
                        >
                            <i class="fas fa-{{ $icon }} text-lg"></i>
                            <span>{{ $deleteText }}</span>
                        </button>
                    </form>

                    <!-- Cancel Button -->
                    <button
                        @click="isOpen = false"
                        class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 py-3 md:py-4 rounded-xl md:rounded-2xl font-black text-xs md:text-sm uppercase transition-all transform hover:scale-105"
                    >
                        {{ $cancelText }}
                    </button>
                </div>

                <!-- Extra Info -->
                <p class="text-[10px] text-slate-400 text-center mt-4 uppercase tracking-widest font-bold">
                    id: {{ $id }}
                </p>
            </div>
        </div>
    </div>
</div>
