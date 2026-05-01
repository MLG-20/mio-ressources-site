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

    protected static ?string $pollingInterval = '30s'; // Rafraîchissement automatique

    public static function canView(): bool
    {
        return auth()->user()?->hasPermission('stats')
            && request()->get('tab') === 'visites';
    }

    protected function getData(): array
    {
        $categories = [
            'Accueil'   => ['label' => 'Accueil',     'color' => '#3b82f6', 'prefix' => false],
            'Semestre'  => ['label' => 'Semestres',   'color' => '#8b5cf6', 'prefix' => true],
            'Matière'   => ['label' => 'Matières',    'color' => '#10b981', 'prefix' => true],
            'Page'      => ['label' => 'Pages info',  'color' => '#f59e0b', 'prefix' => true],
            'Document'  => ['label' => 'Documents',   'color' => '#ec4899', 'prefix' => true],
            'Forum'     => ['label' => 'Forum',       'color' => '#06b6d4', 'prefix' => true],
        ];

        $labels = [];
        $data = [];
        $backgroundColors = [];

        foreach ($categories as $key => $cat) {
            $count = $cat['prefix']
                ? Visit::where('page_visited', 'LIKE', $key . ' : %')->count()
                : Visit::where('page_visited', $key)->count();

            if ($count > 0) {
                $labels[] = $cat['label'];
                $data[] = $count;
                $backgroundColors[] = $cat['color'];
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
