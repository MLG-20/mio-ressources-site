<?php

namespace App\Filament\Widgets;

use App\Models\Ressource;
use Filament\Widgets\ChartWidget;

class ResourcesDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Répartition des Ressources';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 1;

    protected static ?string $pollingInterval = '30s'; // Rafraîchissement automatique

    public static function canView(): bool
    {
        return auth()->user()?->hasPermission('ressources')
            && request()->get('tab') === 'contenus';
    }

    protected function getData(): array
    {
        $data = Ressource::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Nombre de ressources',
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => [
                        '#ef4444',
                        '#f59e0b',
                        '#10b981',
                        '#3b82f6',
                        '#8b5cf6',
                        '#ec4899',
                    ],
                ],
            ],
            'labels' => $data->pluck('type')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
