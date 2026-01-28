<?php

namespace App\Filament\Resources\ForumMessageResource\Pages;

use App\Filament\Resources\ForumMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditForumMessage extends EditRecord
{
    protected static string $resource = ForumMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
