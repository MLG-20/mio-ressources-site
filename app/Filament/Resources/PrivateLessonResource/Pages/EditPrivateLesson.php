<?php

namespace App\Filament\Resources\PrivateLessonResource\Pages;

use App\Filament\Resources\PrivateLessonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrivateLesson extends EditRecord
{
    protected static string $resource = PrivateLessonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
