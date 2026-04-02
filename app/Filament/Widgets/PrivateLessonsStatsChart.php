<?php

namespace App\Filament\Widgets;

use App\Models\PrivateLesson;
use Filament\Widgets\ChartWidget;

class PrivateLessonsStatsChart extends ChartWidget
{
    protected static ?string $heading = 'Cours Particuliers - Statut';

    protected static ?string $description = 'Répartition des états';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 1;

    protected static ?string $pollingInterval = '30s'; // Rafraîchissement automatique

    public static function canView(): bool
    {
        return auth()->user()?->hasPermission('cours')
            && request()->get('tab') === 'contenus';
    }

    protected function getData(): array
    {
        $data = PrivateLesson::groupBy('statut')
            ->selectRaw('statut, COUNT(*) as count')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Nombre de cours',
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => [
                        '#10b981',
                        '#6b7280',
                        '#f59e0b',
                    ],
                ],
            ],
            'labels' => $data->map(fn ($item) => match($item->statut) {
                'actif' => 'Actifs',
                'inactif' => 'Inactifs',
                'complet' => 'Complets',
                default => $item->statut,
            })->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
