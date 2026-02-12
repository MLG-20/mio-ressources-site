<x-filament-panels::page>
    <div x-data="{ activeTab: '{{ request()->get('tab', 'vue-ensemble') }}' }" class="space-y-6">
        <!-- Navigation par onglets -->
        <div class="flex gap-2 overflow-x-auto pb-2">
            <a href="?tab=vue-ensemble"
               @click.prevent="activeTab = 'vue-ensemble'; window.location.href = '?tab=vue-ensemble'"
               :class="activeTab === 'vue-ensemble' ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400'"
               class="px-6 py-3 rounded-lg font-bold text-sm whitespace-nowrap transition-all shadow hover:shadow-lg">
                <span class="flex items-center gap-2">
                    <x-heroicon-o-squares-2x2 class="w-5 h-5" />
                    <span>📊 Vue d'ensemble</span>
                </span>
            </a>

            <a href="?tab=visites"
               @click.prevent="activeTab = 'visites'; window.location.href = '?tab=visites'"
               :class="activeTab === 'visites' ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400'"
               class="px-6 py-3 rounded-lg font-bold text-sm whitespace-nowrap transition-all shadow hover:shadow-lg">
                <span class="flex items-center gap-2">
                    <x-heroicon-o-eye class="w-5 h-5" />
                    <span>� Visites</span>
                </span>
            </a>

            <a href="?tab=finances"
               @click.prevent="activeTab = 'finances'; window.location.href = '?tab=finances'"
               :class="activeTab === 'finances' ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400'"
               class="px-6 py-3 rounded-lg font-bold text-sm whitespace-nowrap transition-all shadow hover:shadow-lg">
                <span class="flex items-center gap-2">
                    <x-heroicon-o-banknotes class="w-5 h-5" />
                    <span>💰 Finances</span>
                </span>
            </a>

            <a href="?tab=contenus"
               @click.prevent="activeTab = 'contenus'; window.location.href = '?tab=contenus'"
               :class="activeTab === 'contenus' ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400'"
               class="px-6 py-3 rounded-lg font-bold text-sm whitespace-nowrap transition-all shadow hover:shadow-lg">
                <span class="flex items-center gap-2">
                    <x-heroicon-o-book-open class="w-5 h-5" />
                    <span>📚 Contenus</span>
                </span>
            </a>

            <a href="?tab=utilisateurs"
               @click.prevent="activeTab = 'utilisateurs'; window.location.href = '?tab=utilisateurs'"
               :class="activeTab === 'utilisateurs' ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400'"
               class="px-6 py-3 rounded-lg font-bold text-sm whitespace-nowrap transition-all shadow hover:shadow-lg">
                <span class="flex items-center gap-2">
                    <x-heroicon-o-users class="w-5 h-5" />
                    <span>👥 Utilisateurs</span>
                </span>
            </a>
                <a href="?tab=avis"
                   @click.prevent="activeTab = 'avis'; window.location.href = '?tab=avis'"
                   :class="activeTab === 'avis' ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400'"
                   class="px-6 py-3 rounded-lg font-bold text-sm whitespace-nowrap transition-all shadow hover:shadow-lg">
                    <span class="flex items-center gap-2">
                        <x-heroicon-o-star class="w-5 h-5" />
                        <span>⭐ Notes & Avis</span>
                    </span>
                </a>
        </div>

        <!-- Contenu des widgets -->
            <template x-if="activeTab === 'avis'">
                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-4">Derniers avis reçus</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full border rounded-2xl shadow bg-white dark:bg-gray-900 overflow-hidden">
                            <thead class="text-xs md:text-sm">
                                <tr class="bg-gray-100 dark:bg-gray-800">
                                    <th class="px-4 py-2 border-b text-gray-700 dark:text-gray-200 first:rounded-tl-2xl last:rounded-tr-2xl">Utilisateur</th>
                                    <th class="px-4 py-2 border-b text-gray-700 dark:text-gray-200">Document</th>
                                    <th class="px-4 py-2 border-b text-gray-700 dark:text-gray-200">Note</th>
                                    <th class="px-4 py-2 border-b text-gray-700 dark:text-gray-200">Commentaire</th>
                                    <th class="px-4 py-2 border-b text-gray-700 dark:text-gray-200 last:rounded-tr-2xl">Date</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs md:text-sm">
                                @foreach(\App\Models\ResourceRating::with(['user','ressource','publication'])->latest()->limit(5)->get() as $loopIndex => $avis)
                                    <tr class="border-b border-gray-100 dark:border-gray-800">
                                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100 {{ $loop->first ? 'rounded-tl-2xl' : '' }} {{ $loop->last ? 'rounded-bl-2xl' : '' }}">{{ $avis->user->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $avis->ressource->titre ?? $avis->publication->titre ?? 'N/A' }}</td>
                                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{!! str_repeat('⭐', $avis->stars) !!}</td>
                                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $avis->comment }}</td>
                                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100 {{ $loop->first ? 'rounded-tr-2xl' : '' }} {{ $loop->last ? 'rounded-br-2xl' : '' }}">{{ $avis->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            </table>
                        </div>
                        <a href="/admin/resource-ratings" class="inline-flex items-center mt-6 px-4 py-2 bg-primary-600 text-white rounded shadow hover:bg-primary-700">
                            <x-heroicon-o-star class="w-5 h-5 mr-2" />
                            Accéder à la gestion complète des avis
                        </a>
                    </div>
            </template>
            <template x-if="activeTab !== 'avis'">
                <x-filament-widgets::widgets
                    :columns="$this->getColumns()"
                    :widgets="$this->getVisibleWidgets()"
                />
            </template>
    </div>
</x-filament-panels::page>
