<?php

namespace App\Filament\Widgets;

use App\Models\Matiere;
use App\Models\Ressource;
use App\Models\User;
use App\Models\Visit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Review;
use App\Models\Purchase;

class StatsOverview extends BaseWidget
{
     // On force l'affichage sur toute la largeur
    // protected int | string | array $columnSpan = 'full';
    
    // On le met en premier (position 1)
    protected static ?int $sort = 1;

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

        Stat::make('Satisfaction', number_format(Review::avg('note') ?? 0, 1) . ' / 5')
           ->description(Review::count() . ' avis reçus')
           ->descriptionIcon('heroicon-m-star')
           ->color('warning'),
    ];
}
}