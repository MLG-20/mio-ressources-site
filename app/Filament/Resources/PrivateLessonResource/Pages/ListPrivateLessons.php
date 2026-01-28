<?php

namespace App\Filament\Resources\PrivateLessonResource\Pages;

use App\Filament\Resources\PrivateLessonResource;
use App\Filament\Widgets\PrivateLessonsStatsChart;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrivateLessons extends ListRecords
{
    protected static string $resource = PrivateLessonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PrivateLessonsStatsChart::class,
        ];
    }
}
