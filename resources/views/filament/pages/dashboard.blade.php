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
        </div>

        <!-- Contenu des widgets -->
        <x-filament-widgets::widgets
            :columns="$this->getColumns()"
            :widgets="$this->getVisibleWidgets()"
        />
    </div>
</x-filament-panels::page>
