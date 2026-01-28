<?php

namespace App\Filament\Resources\ForumMessageResource\Pages;

use App\Filament\Resources\ForumMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateForumMessage extends CreateRecord
{
    protected static string $resource = ForumMessageResource::class;
}
