<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestStudentsWidget extends BaseWidget
{
    protected static ?string $heading = 'Derniers Étudiants Inscrits';
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = '1'; // Prend la moitié de l'écran

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()->where('role', 'etudiant')->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular()
                    ->defaultImageUrl(url('https://ui-avatars.com/api/?background=0D8ABC&color=fff')),
                Tables\Columns\TextColumn::make('name')->label('Étudiant')->weight('bold'),
                Tables\Columns\TextColumn::make('student_level')
                    ->label('Niveau')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('created_at')->label('Inscrit')->since(),
            ])
            ->paginated(false);
    }
}