<?php

namespace App\Filament\Resources\WorkGroupResource\Pages;

use App\Filament\Resources\WorkGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorkGroup extends EditRecord
{
    protected static string $resource = WorkGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
