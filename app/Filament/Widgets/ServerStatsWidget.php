<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\File;

class ServerStatsWidget extends BaseWidget
{
    // On le met tout en haut pour l'avoir sous les yeux
    protected static ?int $sort = 8; 

    protected function getStats(): array
    {
        // 1. Calculer la taille du dossier public (en Octets)
        $size = 0;
        $path = storage_path('app/public');
        
        if (File::exists($path)) {
            foreach (File::allFiles($path) as $file) {
                $size += $file->getSize();
            }
        }

        // 2. Convertir en Mégaoctets (MB)
        $sizeInMB = round($size / 1024 / 1024, 2);
        
        // 3. Définir une alerte (ex: Si > 500 MB)
        $color = $sizeInMB > 500 ? 'danger' : 'success';
        $icon = $sizeInMB > 500 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-server';
        $description = $sizeInMB > 500 ? 'Attention : Espace critique' : 'Stockage sain';

        return [
            Stat::make('Espace Disque Utilisé', $sizeInMB . ' MB')
                ->description($description)
                ->descriptionIcon($icon)
                ->color($color)
                ->chart([$sizeInMB - 10, $sizeInMB - 5, $sizeInMB]), // Petit graphique d'évolution simulé
        ];
    }
}