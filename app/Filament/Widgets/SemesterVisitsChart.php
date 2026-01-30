<?php

namespace App\Filament\Widgets;

use App\Models\Visit;
use App\Models\Semestre;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SemesterVisitsChart extends ChartWidget
{
    protected static ?string $heading = 'Visites par Semestre';

    protected static ?string $description = 'Suivi des visites sur les pages de semestre';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 1;

    public static function canView(): bool
    {
        return request()->get('tab') === 'visites';
    }

    protected function getData(): array
    {
        // Récupérer tous les semestres
        $semestres = Semestre::all();

        $labels = [];
        $data = [];

        foreach ($semestres as $semestre) {
            // Compter les visites pour chaque semestre
            // On cherche les URLs qui contiennent le slug ou l'ID du semestre
            $count = Visit::where('page_visited', 'LIKE', '%/semestre/' . $semestre->id . '%')
                ->orWhere('page_visited', 'LIKE', '%browse%semestre=' . $semestre->id . '%')
                ->count();

            $labels[] = $semestre->nom . ' (' . $semestre->niveau . ')';
            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Visites',
                    'data' => $data,
                    'backgroundColor' => [
                        '#3b82f6', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#06b6d4'
                    ],
                    'borderRadius' => 8,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
