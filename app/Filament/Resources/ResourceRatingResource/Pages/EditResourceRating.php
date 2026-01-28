<?php

namespace App\Filament\Resources\ResourceRatingResource\Pages;

use App\Filament\Resources\ResourceRatingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResourceRating extends EditRecord
{
    protected static string $resource = ResourceRatingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
