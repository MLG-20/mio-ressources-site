<?php

namespace App\Filament\Widgets;

use App\Models\ResourceRating;
use Filament\Widgets\ChartWidget;

class ResourceRatingsChart extends ChartWidget
{
    protected static ?string $heading = 'Distribution des Évaluations';

    protected static ?string $description = 'Répartition des notes (1-5 étoiles)';

    protected static ?int $sort = 4;

    public static function canView(): bool
    {
        return request()->get('tab') === 'contenus';
    }

    protected function getData(): array
    {
        $data = ResourceRating::groupBy('stars')
            ->selectRaw('stars, COUNT(*) as count')
            ->orderBy('stars')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Nombre d\'avis',
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => [
                        '#ef4444',
                        '#f97316',
                        '#eab308',
                        '#84cc16',
                        '#22c55e',
                    ],
                ],
            ],
            'labels' => $data->pluck('stars')->map(fn ($stars) => str_repeat('⭐', $stars))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
