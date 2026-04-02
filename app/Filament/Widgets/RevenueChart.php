<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Purchase;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Répartition des Revenus (Mois en cours)';
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 1;

    public static function canView(): bool
    {
        return auth()->user()?->hasPermission('paiements')
            && request()->get('tab') === 'finances';
    }

    protected function getData(): array
    {
        // On récupère toutes les ventes
        $totalSales = Purchase::sum('amount');

        // On calcule ta commission (ex: 30%)
        $myCommission = $totalSales * 0.30;

        // On calcule la part reversée aux profs (70%)
        $teachersShare = $totalSales * 0.70;

        return [
            'datasets' => [
                [
                    'label' => 'Montant (CFA)',
                    'data' => [$teachersShare, $myCommission],
                    'backgroundColor' => ['#3b82f6', '#10b981'], // Bleu pour eux, Vert pour toi
                ],
            ],
            'labels' => ['Part Professeurs (70%)', 'Ma Commission (30%)'],
        ];
    }

    protected function getType(): string { return 'doughnut'; }
}
