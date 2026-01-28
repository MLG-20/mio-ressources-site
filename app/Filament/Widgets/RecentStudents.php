<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentStudents extends BaseWidget
{
    protected static ?string $heading = 'Derniers Étudiants Inscrits';

    protected static ?int $sort = 8;

    protected int | string | array $columnSpan = 1;

    public static function canView(): bool
    {
        return false; // Désactivé - doublon avec LatestStudentsWidget
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()->where('role', 'student')
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
