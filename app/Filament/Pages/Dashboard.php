<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    use BaseDashboard\Concerns\HasFiltersForm;

    protected static ?string $navigationLabel = 'Tableau de bord';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.dashboard';

    public function getColumns(): int | string | array
    {
        $tab = request()->get('tab', 'vue-ensemble');

        return match($tab) {
            'vue-ensemble' => [
                'default' => 1,
                'sm' => 2,
                'md' => 3,
                'lg' => 4,
            ],
            'visites' => [
                'default' => 1,
                'md' => 2,
            ],
            'finances' => [
                'default' => 1,
                'md' => 2,
            ],
            'contenus' => [
                'default' => 1,
                'sm' => 2,
                'md' => 3,
            ],
            'utilisateurs' => [
                'default' => 1,
                'md' => 2,
            ],
            default => 2,
        };
    }
}
