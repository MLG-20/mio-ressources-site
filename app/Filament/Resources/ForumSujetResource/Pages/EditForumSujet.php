<?php

namespace App\Filament\Resources\ForumSujetResource\Pages;

use App\Filament\Resources\ForumSujetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditForumSujet extends EditRecord
{
    protected static string $resource = ForumSujetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
