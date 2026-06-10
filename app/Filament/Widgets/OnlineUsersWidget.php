<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OnlineUsersWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    // Rafraîchissement automatique du compteur
    protected static ?string $pollingInterval = '30s';

    public static function canView(): bool
    {
        if (! auth()->user()?->hasPermission('users')) {
            return false;
        }

        return request()->get('tab') === 'vue-ensemble' || request()->get('tab') === null;
    }

    protected function getStats(): array
    {
        $online    = User::online()->count();
        $etudiants = User::online()->where('role', 'etudiant')->count();
        $profs     = User::online()->where('role', 'professeur')->count();

        return [
            Stat::make('Utilisateurs en ligne', $online)
                ->description($online > 0 ? 'Actifs ces ' . User::ONLINE_THRESHOLD_MINUTES . ' dernières minutes' : 'Personne pour le moment')
                ->descriptionIcon('heroicon-m-signal')
                ->color($online > 0 ? 'success' : 'gray'),

            Stat::make('Étudiants en ligne', $etudiants)
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('info'),

            Stat::make('Enseignants en ligne', $profs)
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),
        ];
    }
}
