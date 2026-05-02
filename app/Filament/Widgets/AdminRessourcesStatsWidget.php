<?php

namespace App\Filament\Widgets;

use App\Models\Ressource;
use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class AdminRessourcesStatsWidget extends BaseWidget
{
    protected static ?string $heading = 'Ressources ajoutées par chaque administrateur';
    protected static ?int $sort = 10;
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return (auth()->user()?->isSuperAdmin() ?? false)
            && request()->get('tab') === 'contenus';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->where('role', 'admin')
                    ->withCount('ressources')
                    ->orderByDesc('ressources_count')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Administrateur')
                    ->weight('bold')
                    ->icon('heroicon-m-user-circle'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->color('gray'),

                Tables\Columns\TextColumn::make('ressources_count')
                    ->label('Ressources ajoutées')
                    ->alignCenter()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state === 0 => 'gray',
                        $state < 5   => 'warning',
                        default      => 'success',
                    }),

                Tables\Columns\TextColumn::make('latest_ressource')
                    ->label('Dernière ressource')
                    ->getStateUsing(fn (User $record) =>
                        $record->ressources()->latest()->first()?->created_at?->format('d/m/Y H:i') ?? '—'
                    )
                    ->color('gray'),
            ])
            ->paginated(false);
    }
}
