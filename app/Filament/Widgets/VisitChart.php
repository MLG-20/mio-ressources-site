<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Visit;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VisitChart extends ChartWidget
{
    protected static ?string $heading = 'Analyse du Trafic';
    public ?string $filter = 'week'; // Filtre par défaut
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return request()->get('tab') === 'visites';
    }
    // 1. Définir le menu déroulant
    protected function getFilters(): ?array
    {
        return [
            'today' => 'Aujourd\'hui',
            'week' => '7 derniers jours',
            'month' => 'Ce mois-ci',
            'year' => 'Cette année',
        ];
    }

    protected function getData(): array
    {
        $query = Visit::query();
        $activeFilter = $this->filter;

        // 2. Appliquer la logique de date
        if ($activeFilter === 'today') {
            // Pour aujourd'hui : on groupe par HEURE
            $query->whereDate('visit_date', Carbon::today());
            $select = "DATE_FORMAT(visit_date, '%H:00')";
        } elseif ($activeFilter === 'week') {
            // Pour la semaine : on groupe par JOUR
            $query->where('visit_date', '>=', now()->subDays(7));
            $select = "DATE_FORMAT(visit_date, '%Y-%m-%d')";
        } elseif ($activeFilter === 'month') {
            // Pour le mois : on groupe par JOUR
            $query->whereMonth('visit_date', now()->month);
            $select = "DATE_FORMAT(visit_date, '%Y-%m-%d')";
        } elseif ($activeFilter === 'year') {
            // Pour l'année : on groupe par MOIS
            $query->whereYear('visit_date', now()->year);
            $select = "DATE_FORMAT(visit_date, '%Y-%m')";
        }

        // 3. Exécuter la requête
        $data = $query->select(
            DB::raw("$select as date"),
            DB::raw('count(*) as views')
        )
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Visiteurs',
                    'data' => $data->pluck('views')->toArray(),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4, // Courbe lissée
                ],
            ],
            'labels' => $data->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string { return 'line'; }
}
