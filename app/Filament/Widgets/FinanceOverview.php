<?php
namespace App\Filament\Widgets;

use App\Models\Purchase;
use App\Models\SubscriptionPayment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

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
        $data = Cache::remember('finance_overview', 600, function () {
            $ventesRessources   = Purchase::where('item_type', 'ressource')->sum('amount');
            $ventesPublications = Purchase::where('item_type', 'publication')->sum('amount');
            $revenusAbonnements = SubscriptionPayment::where('status', 'paid')->sum('amount');

            return [
                'ventesRessources'   => $ventesRessources,
                'ventesPublications' => $ventesPublications,
                'maCommission'       => $ventesPublications * 0.30,
                'duAuxProfs'         => $ventesPublications * 0.70,
                'revenusAbonnements' => $revenusAbonnements,
                'totalPlateforme'    => $ventesRessources + ($ventesPublications * 0.30) + $revenusAbonnements,
            ];
        });

        [
            'ventesRessources'   => $ventesRessources,
            'maCommission'       => $maCommission,
            'duAuxProfs'         => $duAuxProfs,
            'revenusAbonnements' => $revenusAbonnements,
            'totalPlateforme'    => $totalPlateforme,
        ] = $data;

        return [
            Stat::make('Ventes ressources', number_format($ventesRessources, 0, ',', ' ') . ' F')
                ->description('Documents plateforme (100% à vous)')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),

            Stat::make('Commissions Marketplace', number_format($maCommission, 0, ',', ' ') . ' F')
                ->description('30% sur les ventes de publications profs')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),

            Stat::make('Revenus Abonnements', number_format($revenusAbonnements, 0, ',', ' ') . ' F')
                ->description('Total encaissé sur les abonnements étudiants')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('primary'),

            Stat::make('Total Plateforme', number_format($totalPlateforme, 0, ',', ' ') . ' F')
                ->description('Ressources + commissions + abonnements')
                ->descriptionIcon('heroicon-m-building-library')
                ->color('warning'),

            Stat::make('Dû aux Professeurs', number_format($duAuxProfs, 0, ',', ' ') . ' F')
                ->description('70% des ventes de publications à reverser')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('danger'),
        ];
    }
}
