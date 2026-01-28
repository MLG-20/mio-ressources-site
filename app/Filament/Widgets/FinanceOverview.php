<?php
namespace App\Filament\Widgets;

use App\Models\Purchase;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FinanceOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return request()->get('tab') === 'finances';
    }

    protected function getStats(): array
    {
        // 1. CALCUL DES VENTES DIRECTES (Tes documents à toi - ID 1)
        // On prend tout ce qui appartient à l'ID 1 ou ce qui n'a pas d'auteur (Administration par défaut)
        $mesGainsDirects = Purchase::where(function ($query) {
            $query->whereHas('ressource', fn ($q) => $q->where('user_id', 1)->orWhereNull('user_id'))
                  ->orWhereHas('publication', fn ($q) => $q->where('user_id', 1)->orWhereNull('user_id'));
        })->sum('amount');

        // 2. CALCUL DES VENTES DES PROFESSEURS (Tout ce qui n'est PAS à l'ID 1)
        $totalVentesProfs = Purchase::where(function ($query) {
            $query->whereHas('ressource', fn ($q) => $q->where('user_id', '!=', 1))
                  ->orWhereHas('publication', fn ($q) => $q->where('user_id', '!=', 1));
        })->sum('amount');

        // Ta commission de 30% sur les ventes des profs
        $maCommission = $totalVentesProfs * 0.30;

        // Ce que tu dois aux profs (70%)
        $duAuxProfs = $totalVentesProfs * 0.70;

        return [
            // CARTE 1 : Tes propres revenus (100% de l'argent encaissé)
            Stat::make('Mes Ventes Directes', number_format($mesGainsDirects, 0, ',', ' ') . ' F')
                ->description('Revenus sur vos propres documents (100%)')
                ->descriptionIcon('heroicon-m-user')
                ->color('success'),

            // CARTE 2 : Tes commissions sur les autres
            Stat::make('Commissions Marketplace', number_format($maCommission, 0, ',', ' ') . ' F')
                ->description('Votre part de 30% sur les ventes des profs')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),

            // CARTE 3 : L'argent que tu dois sortir de ton compte pour les profs
            Stat::make('Dû aux Professeurs', number_format($duAuxProfs, 0, ',', ' ') . ' F')
                ->description('Total à reverser aux enseignants (70%)')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('danger'),
        ];
    }
}
