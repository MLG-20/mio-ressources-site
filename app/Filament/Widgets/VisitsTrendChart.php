<?php

namespace App\Filament\Widgets;

use App\Models\Visit;
use Filament\Widgets\ChartWidget;

class VisitsTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Tendance des Visites';

    protected static ?string $description = 'Visites enregistrées';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 1;

    public static function canView(): bool
    {
        return request()->get('tab') === 'visites';
    }

    protected function getData(): array
    {
        // Compter les visites par page
        $data = Visit::groupBy('page_visited')
            ->selectRaw('page_visited, COUNT(*) as count')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Visites',
                    'data' => $data->pluck('count')->toArray(),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data->pluck('page_visited')->map(fn ($page) => str_contains($page, '/') ? explode('/', $page)[1] ?? 'Accueil' : $page)->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
