<?php
namespace App\Filament\Widgets;

use App\Models\Purchase;
use App\Models\SubscriptionPayment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FinanceOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return auth()->user()?->hasPermission('paiements')
            && request()->get('tab') === 'finances';
    }

    protected function getStats(): array
    {
        $mesGainsDirects = Purchase::where(function ($query) {
            $query->whereHas('ressource', fn ($q) => $q->where('user_id', 1)->orWhereNull('user_id'))
                  ->orWhereHas('publication', fn ($q) => $q->where('user_id', 1)->orWhereNull('user_id'));
        })->sum('amount');

        $totalVentesProfs = Purchase::where(function ($query) {
            $query->whereHas('ressource', fn ($q) => $q->where('user_id', '!=', 1))
                  ->orWhereHas('publication', fn ($q) => $q->where('user_id', '!=', 1));
        })->sum('amount');

        $maCommission = $totalVentesProfs * 0.30;
        $duAuxProfs   = $totalVentesProfs * 0.70;

        $revenusAbonnements = SubscriptionPayment::where('status', 'paid')->sum('amount');

        $totalPlateforme = $mesGainsDirects + $maCommission + $revenusAbonnements;

        return [
            Stat::make('Ventes directes (docs)', number_format($mesGainsDirects, 0, ',', ' ') . ' F')
                ->description('100% sur vos propres documents')
                ->descriptionIcon('heroicon-m-user')
                ->color('success'),

            Stat::make('Commissions Marketplace', number_format($maCommission, 0, ',', ' ') . ' F')
                ->description('30% sur les ventes des profs')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),

            Stat::make('Revenus Abonnements', number_format($revenusAbonnements, 0, ',', ' ') . ' F')
                ->description('Total encaissé sur les abonnements étudiants')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('primary'),

            Stat::make('Total Plateforme', number_format($totalPlateforme, 0, ',', ' ') . ' F')
                ->description('Ventes directes + commissions + abonnements')
                ->descriptionIcon('heroicon-m-building-library')
                ->color('warning'),

            Stat::make('Dû aux Professeurs', number_format($duAuxProfs, 0, ',', ' ') . ' F')
                ->description('70% des ventes profs à reverser')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('danger'),
        ];
    }
}
