<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Visit;
use Illuminate\Support\Facades\DB;

class TopPagesChart extends ChartWidget
{
    protected static ?string $heading = 'Pages les plus visitées';
    protected static ?int $sort = 5; // Pour le mettre après les autres

    protected function getData(): array
    {
        // On récupère le top 5 des pages visitées
        $data = Visit::select('page_visited', DB::raw('count(*) as total'))
            ->groupBy('page_visited')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Vues',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#3b82f6', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981'
                    ],
                    'borderRadius' => 5,
                ],
            ],
            'labels' => $data->pluck('page_visited')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Graphique en barres horizontales ou verticales
    }
}