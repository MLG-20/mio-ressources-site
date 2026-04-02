<?php

namespace App\Filament\Widgets;

use App\Models\DownloadHistory;
use App\Models\FinancialTransaction;
use App\Models\User;
use App\Models\Meeting;
use App\Models\PrivateLesson;
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

        Stat::make('Transactions Complétées', FinancialTransaction::where('type', 'CREDIT_VENTE')->where('created_at', '>=', Carbon::now()->startOfMonth())->count())
           ->description('Ce mois-ci')
           ->descriptionIcon('heroicon-m-banknotes')
           ->color('warning'),

        Stat::make('Téléchargements', DownloadHistory::where('downloaded_at', '>=', Carbon::now()->subDays(30))->count())
            ->description('Derniers 30 jours')
            ->descriptionIcon('heroicon-m-arrow-down-tray')
            ->color('info'),
    ];
}
}
