<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestTeachersWidget extends BaseWidget
{
    protected static ?string $heading = 'Nouveaux Enseignants';
    protected static ?int $sort = 7;
    protected int | string | array $columnSpan = '1'; // Prend l'autre moitié de l'écran

    public static function canView(): bool
    {
        return auth()->user()?->hasPermission('users')
            && request()->get('tab') === 'utilisateurs';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()->where('user_type', 'teacher')->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular()
                    ->defaultImageUrl(url('https://ui-avatars.com/api/?background=1e293b&color=fff')),
                Tables\Columns\TextColumn::make('name')->label('Enseignant')->weight('bold'),
                Tables\Columns\TextColumn::make('specialty')
                    ->label('Spécialité')
                    ->badge()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('created_at')->label('Arrivé')->since(),
            ])
            ->paginated(false);
    }
}
