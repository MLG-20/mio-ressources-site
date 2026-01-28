<?php

namespace App\Filament\Resources\PrivateLessonEnrollmentResource\Pages;

use App\Filament\Resources\PrivateLessonEnrollmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrivateLessonEnrollment extends EditRecord
{
    protected static string $resource = PrivateLessonEnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
