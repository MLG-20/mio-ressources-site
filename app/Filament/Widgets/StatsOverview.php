<?php

namespace App\Filament\Widgets;

use App\Models\DownloadHistory;
use App\Models\Purchase;
use App\Models\SubscriptionPayment;
use App\Models\User;
use App\Models\Visit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = null;

    public static function canView(): bool
    {
        if (!auth()->user()?->isSuperAdmin()) {
            return false;
        }
        return request()->get('tab') === 'vue-ensemble' || request()->get('tab') === null;
    }

    private function trend(int|float $current, int|float $previous): array
    {
        if ($previous === 0) {
            return ['label' => $current > 0 ? 'Nouveau' : 'Aucune donnée', 'color' => 'gray', 'icon' => 'heroicon-m-minus'];
        }
        $pct = round((($current - $previous) / $previous) * 100);
        if ($pct > 0) {
            return ['label' => '+' . $pct . '% vs mois dernier', 'color' => 'success', 'icon' => 'heroicon-m-arrow-trending-up'];
        }
        if ($pct < 0) {
            return ['label' => $pct . '% vs mois dernier', 'color' => 'danger', 'icon' => 'heroicon-m-arrow-trending-down'];
        }
        return ['label' => 'Stable vs mois dernier', 'color' => 'gray', 'icon' => 'heroicon-m-minus'];
    }

    protected function getStats(): array
    {
        $now        = Carbon::now();
        $startMonth = $now->copy()->startOfMonth();
        $startPrev  = $now->copy()->subMonth()->startOfMonth();
        $endPrev    = $now->copy()->subMonth()->endOfMonth();

        // Visites
        $visitesThisMonth = Visit::where('visit_date', '>=', $startMonth)->count();
        $visitesPrevMonth = Visit::whereBetween('visit_date', [$startPrev, $endPrev])->count();
        $visitesTrend     = $this->trend($visitesThisMonth, $visitesPrevMonth);

        // Nouveaux utilisateurs
        $usersThis  = User::where('created_at', '>=', $startMonth)->count();
        $usersPrev  = User::whereBetween('created_at', [$startPrev, $endPrev])->count();
        $usersTrend = $this->trend($usersThis, $usersPrev);

        // Achats
        $achatsThis  = Purchase::where('created_at', '>=', $startMonth)->count();
        $achatsPrev  = Purchase::whereBetween('created_at', [$startPrev, $endPrev])->count();
        $achatsTrend = $this->trend($achatsThis, $achatsPrev);

        // Téléchargements
        $dlThis  = DownloadHistory::where('downloaded_at', '>=', $now->copy()->subDays(30))->count();
        $dlPrev  = DownloadHistory::whereBetween('downloaded_at', [$now->copy()->subDays(60), $now->copy()->subDays(30)])->count();
        $dlTrend = $this->trend($dlThis, $dlPrev);

        // Revenus abonnements
        $subThis  = SubscriptionPayment::where('status', 'paid')->where('paid_at', '>=', $startMonth)->sum('amount');
        $subPrev  = SubscriptionPayment::where('status', 'paid')->whereBetween('paid_at', [$startPrev, $endPrev])->sum('amount');
        $subTrend = $this->trend($subThis, $subPrev);

        return [
            Stat::make('Visites ce mois', $visitesThisMonth)
                ->description($visitesTrend['label'])
                ->descriptionIcon($visitesTrend['icon'])
                ->color($visitesTrend['color']),

            Stat::make('Nouveaux utilisateurs', $usersThis)
                ->description($usersTrend['label'])
                ->descriptionIcon($usersTrend['icon'])
                ->color($usersTrend['color']),

            Stat::make('Achats ce mois', $achatsThis)
                ->description($achatsTrend['label'])
                ->descriptionIcon($achatsTrend['icon'])
                ->color($achatsTrend['color']),

            Stat::make('Téléchargements (30j)', $dlThis)
                ->description($dlTrend['label'])
                ->descriptionIcon($dlTrend['icon'])
                ->color($dlTrend['color']),

            Stat::make('Abonnés actifs', User::where('subscription_paid_until', '>', $now)->count())
                ->description('Étudiants avec abonnement en cours')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success'),

            Stat::make('Revenus abonnements', number_format($subThis, 0, ',', ' ') . ' F')
                ->description($subTrend['label'])
                ->descriptionIcon($subTrend['icon'])
                ->color($subTrend['color']),
        ];
    }
}
