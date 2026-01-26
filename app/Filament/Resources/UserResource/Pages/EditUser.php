<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function mount(int | string $record): void
    {
        parent::mount($record);

        // Si on essaie de toucher au Super Admin (ID 1) sans l'être
        if ($this->record->id === 1 && Auth::id() !== 1) {
            
            // 1. On envoie une notification d'erreur
            Notification::make()
                ->title('Accès refusé')
                ->body('Vous ne pouvez pas modifier le compte du Super Admin.')
                ->danger()
                ->send();

            // 2. On redirige immédiatement vers la liste
            $this->redirect($this->getResource()::getUrl('index'));
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->hidden(fn () => $this->record->id === 1),
        ];
    }
}