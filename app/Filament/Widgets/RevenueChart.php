<?php

namespace App\Filament\Widgets;

use App\Models\Purchase;
use App\Models\SubscriptionPayment;
use Filament\Widgets\ChartWidget;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Répartition des Revenus (Tout le temps)';
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 1;

    public static function canView(): bool
    {
        return auth()->user()?->hasPermission('paiements')
            && request()->get('tab') === 'finances';
    }

    protected function getData(): array
    {
        $ventesRessources   = Purchase::where('item_type', 'ressource')->sum('amount');
        $ventesPublications = Purchase::where('item_type', 'publication')->sum('amount');
        $maCommission       = $ventesPublications * 0.30;
        $duAuxProfs         = $ventesPublications * 0.70;
        $abonnements        = SubscriptionPayment::where('status', 'paid')->sum('amount');

        $data   = [];
        $labels = [];
        $colors = [];

        if ($ventesRessources > 0) {
            $data[] = $ventesRessources; $labels[] = 'Ressources (100%)'; $colors[] = '#10b981';
        }
        if ($maCommission > 0) {
            $data[] = $maCommission; $labels[] = 'Commissions profs (30%)'; $colors[] = '#3b82f6';
        }
        if ($abonnements > 0) {
            $data[] = $abonnements; $labels[] = 'Abonnements'; $colors[] = '#8b5cf6';
        }
        if ($duAuxProfs > 0) {
            $data[] = $duAuxProfs; $labels[] = 'Dû aux profs (70%)'; $colors[] = '#f59e0b';
        }

        return [
            'datasets' => [
                [
                    'label' => 'Montant (CFA)',
                    'data' => $data,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string { return 'doughnut'; }
}
