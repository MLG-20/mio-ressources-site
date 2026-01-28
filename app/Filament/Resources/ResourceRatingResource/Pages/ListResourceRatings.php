<?php

namespace App\Filament\Resources\ResourceRatingResource\Pages;

use App\Filament\Resources\ResourceRatingResource;
use App\Filament\Widgets\ResourceRatingsChart;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResourceRatings extends ListRecords
{
    protected static string $resource = ResourceRatingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ResourceRatingsChart::class,
        ];
    }
}
