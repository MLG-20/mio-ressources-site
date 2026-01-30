<?php

namespace App\Filament\Resources\RessourceResource\Pages;

use App\Filament\Resources\RessourceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRessources extends ListRecords
{
    protected static string $resource = RessourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('dashboard')
                ->label('← Tableau de bord')
                ->url(fn () => route('filament.admin.pages.dashboard'))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),
            Actions\CreateAction::make(),
        ];
    }
}
