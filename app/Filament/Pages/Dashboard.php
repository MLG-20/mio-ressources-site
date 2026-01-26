<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    // On définit une grille de 2 colonnes
    public function getColumns(): int | array
    {
        return 2;
    }
}