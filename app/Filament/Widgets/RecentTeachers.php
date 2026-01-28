<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentTeachers extends BaseWidget
{
    protected static ?string $heading = 'Nouveaux Enseignants';

    protected static ?int $sort = 9;

    protected int | string | array $columnSpan = 1;

    public static function canView(): bool
    {
        return false; // Désactivé - doublon avec LatestTeachersWidget
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()->where('role', 'teacher')
                    ->orderByDesc('created_at')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Inscrit le')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ]);
    }
}
