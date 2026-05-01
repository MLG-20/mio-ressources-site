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
        $totalDocs = Purchase::sum('amount');
        $myDirectSales = Purchase::where(function ($q) {
            $q->whereHas('ressource', fn ($r) => $r->where('user_id', 1)->orWhereNull('user_id'))
              ->orWhereHas('publication', fn ($r) => $r->where('user_id', 1)->orWhereNull('user_id'));
        })->sum('amount');
        $profsShare = ($totalDocs - $myDirectSales) * 0.70;
        $myCommission = ($totalDocs - $myDirectSales) * 0.30;
        $subscriptions = SubscriptionPayment::where('status', 'paid')->sum('amount');

        return [
            'datasets' => [
                [
                    'label' => 'Montant (CFA)',
                    'data' => [$myDirectSales, $myCommission, $subscriptions, $profsShare],
                    'backgroundColor' => ['#10b981', '#3b82f6', '#8b5cf6', '#f59e0b'],
                ],
            ],
            'labels' => ['Ventes directes', 'Commissions profs', 'Abonnements', 'Dû aux profs'],
        ];
    }

    protected function getType(): string { return 'doughnut'; }
}
