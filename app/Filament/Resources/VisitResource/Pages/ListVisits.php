<?php

namespace App\Filament\Resources\VisitResource\Pages;

use App\Filament\Resources\VisitResource;
use App\Filament\Widgets\VisitsTrendChart;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVisits extends ListRecords
{
    protected static string $resource = VisitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->hidden(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            VisitsTrendChart::class,
        ];
    }
}
