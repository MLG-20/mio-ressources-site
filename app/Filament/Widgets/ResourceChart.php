<?php

namespace App\Filament\Widgets;

use App\Models\Ressource;
use Filament\Widgets\ChartWidget;

class ResourceChart extends ChartWidget
{
    protected static ?string $heading = 'Répartition des Ressources';
    protected static ?int $sort = 4; // Position 3
    protected int | string | array $columnSpan = 1; // 50% de la largeur

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Ressources',
                    'data' => [
                        Ressource::where('type', 'Cours')->count(),
                        Ressource::where('type', 'TD')->count(),
                        Ressource::where('type', 'Vidéo')->count(),
                    ],
                    'backgroundColor' => ['#36A2EB', '#FF6384', '#FFCE56'],
                ],
            ],
            'labels' => ['Cours', 'TD', 'Vidéos'],
        ];
    }

    protected function getType(): string { return 'doughnut'; }
}