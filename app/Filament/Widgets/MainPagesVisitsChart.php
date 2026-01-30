<?php

namespace App\Filament\Widgets;

use App\Models\Visit;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MainPagesVisitsChart extends ChartWidget
{
    protected static ?string $heading = 'Pages Principales les plus visitées';

    protected static ?string $description = 'Visites sur les pages clés du site';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 1;

    public static function canView(): bool
    {
        return request()->get('tab') === 'visites';
    }

    protected function getData(): array
    {
        // Définir les pages principales à surveiller
        $mainPages = [
            '/' => 'Accueil',
            '/browse' => 'Parcourir les Cours',
            '/publications' => 'Publications',
            '/cours-particuliers' => 'Cours Particuliers',
            '/meetings' => 'Réunions',
            '/forum' => 'Forum',
            '/contact' => 'Contact',
            '/about' => 'À Propos',
        ];

        $labels = [];
        $data = [];
        $colors = [
            '#3b82f6', '#8b5cf6', '#ec4899', '#f59e0b',
            '#10b981', '#06b6d4', '#f43f5e', '#8b5cf6'
        ];

        $colorIndex = 0;
        $backgroundColors = [];

        foreach ($mainPages as $url => $label) {
            // Compter les visites exactes pour cette page
            $count = Visit::where('page_visited', $url)
                ->orWhere('page_visited', 'LIKE', $url . '?%')
                ->count();

            if ($count > 0) {
                $labels[] = $label;
                $data[] = $count;
                $backgroundColors[] = $colors[$colorIndex % count($colors)];
                $colorIndex++;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Visites',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                    'borderRadius' => 8,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut'; // Graphique en cercle pour mieux visualiser
    }
}
