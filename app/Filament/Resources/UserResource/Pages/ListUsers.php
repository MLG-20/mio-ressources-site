<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab; // Import Important
use Illuminate\Database\Eloquent\Builder; // Import Important

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('dashboard')
                ->label('← Tableau de bord')
                ->url(fn () => route('filament.admin.pages.dashboard'))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),
            Actions\CreateAction::make(),
        ];
    }

    // C'EST CETTE FONCTION QUI CRÉE LES ONGLETS
    public function getTabs(): array
    {
        return [
            'tous' => Tab::make('Tous les utilisateurs'),

            'admins' => Tab::make('Administrateurs')
                ->icon('heroicon-m-shield-check')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('role', 'admin')),

            'profs' => Tab::make('Enseignants')
                ->icon('heroicon-m-academic-cap')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('user_type', 'teacher')),

            'etudiants' => Tab::make('Étudiants')
                ->icon('heroicon-m-users')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('role', 'etudiant')),
        ];
    }
}
