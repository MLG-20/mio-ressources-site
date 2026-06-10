<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class OnlineUsers extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-signal';

    protected static ?string $navigationGroup = 'Gestion du site';

    protected static ?string $navigationLabel = 'Utilisateurs en ligne';

    protected static ?string $title = 'Utilisateurs en ligne';

    protected static string $view = 'filament.pages.online-users';

    /**
     * SÉCURITÉ : visible uniquement pour les admins ayant la permission 'users'.
     */
    public static function canAccess(): bool
    {
        return auth()->user()?->hasPermission('users') ?? false;
    }

    /**
     * Badge dans le menu : nombre d'utilisateurs en ligne.
     */
    public static function getNavigationBadge(): ?string
    {
        $count = User::online()->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()->online()->orderByDesc('last_seen_at')
            )
            ->poll('10s')
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(url('https://ui-avatars.com/api/?background=0D8ABC&color=fff')),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('role')
                    ->label('Rôle')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin'      => 'danger',
                        'professeur' => 'warning',
                        'etudiant'   => 'info',
                        default      => 'gray',
                    }),

                Tables\Columns\TextColumn::make('student_level')
                    ->label('Niveau')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('last_seen_at')
                    ->label('Dernière activité')
                    ->since()
                    ->sortable()
                    ->description(fn (User $record): string => '🟢 En ligne'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Rôle')
                    ->options([
                        'etudiant'   => 'Étudiant',
                        'professeur' => 'Enseignant',
                        'admin'      => 'Admin',
                    ]),
            ])
            ->emptyStateHeading('Personne en ligne')
            ->emptyStateDescription('Aucun utilisateur actif ces ' . User::ONLINE_THRESHOLD_MINUTES . ' dernières minutes.')
            ->emptyStateIcon('heroicon-o-signal-slash');
    }
}
