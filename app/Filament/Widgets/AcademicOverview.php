<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AcademicOverview extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            //
            Stat::make('Matières', \App\Models\Matiere::count())
            ->description('Cours configurés')
            ->icon('heroicon-m-academic-cap')
            ->color('info'),

        Stat::make('Ressources', \App\Models\Ressource::count())
            ->description('Documents PDF & Vidéos')
            ->icon('heroicon-m-document-text'),

        Stat::make('Livres Académiques', \App\Models\Publication::count())
            ->description('Ouvrages des professeurs')
            ->icon('heroicon-m-book-open')
            ->color('success'),
        ];
    }
}
