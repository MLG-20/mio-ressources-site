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
        // Ressources n'ont pas d'auteur (pas de user_id) → 100% plateforme
        $ventesRessources = Purchase::where('item_type', 'ressource')->sum('amount');

        // Publications ont un auteur (user_id) → split 30% plateforme / 70% prof
        $ventesPublications = Purchase::where('item_type', 'publication')->sum('amount');
        $maCommission       = $ventesPublications * 0.30;
        $duAuxProfs         = $ventesPublications * 0.70;

        $revenusAbonnements = SubscriptionPayment::where('status', 'paid')->sum('amount');

        $totalPlateforme = $ventesRessources + $maCommission + $revenusAbonnements;

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
