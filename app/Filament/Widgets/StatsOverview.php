<?php

namespace App\Filament\Widgets;

use App\Models\DownloadHistory;
use App\Models\Purchase;
use App\Models\SubscriptionPayment;
use App\Models\User;
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

    protected function getStats(): array
{
    return [
        Stat::make('Visites totales', \App\Models\Visit::count())
            ->description('Nombre de pages vues')
            ->descriptionIcon('heroicon-m-eye')
            ->color('success'),

        Stat::make('Utilisateurs', User::count())
            ->description('Inscrits sur la plateforme')
            ->descriptionIcon('heroicon-m-users')
            ->color('primary'),

        Stat::make('Achats ce mois', Purchase::where('created_at', '>=', Carbon::now()->startOfMonth())->count())
           ->description('Documents & abonnements vendus')
           ->descriptionIcon('heroicon-m-banknotes')
           ->color('warning'),

        Stat::make('Téléchargements', DownloadHistory::where('downloaded_at', '>=', Carbon::now()->subDays(30))->count())
            ->description('Derniers 30 jours')
            ->descriptionIcon('heroicon-m-arrow-down-tray')
            ->color('info'),

        Stat::make('Abonnés actifs', User::where('subscription_paid_until', '>', Carbon::now())->count())
            ->description('Étudiants avec abonnement en cours')
            ->descriptionIcon('heroicon-m-academic-cap')
            ->color('success'),

        Stat::make('Revenus abonnements', number_format(SubscriptionPayment::where('status', 'paid')->whereMonth('paid_at', Carbon::now()->month)->sum('amount'), 0, ',', ' ') . ' F')
            ->description('Ce mois-ci')
            ->descriptionIcon('heroicon-m-credit-card')
            ->color('primary'),
    ];
}
}
