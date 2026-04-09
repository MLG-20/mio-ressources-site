<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResourceRatingResource\Pages;
use App\Models\ResourceRating;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ResourceRatingResource extends Resource
{
    protected static ?string $model = ResourceRating::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationLabel = 'Évaluations Ressources';

    protected static ?string $navigationGroup = 'Statistiques';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        $user = Auth::user();

        if (! $user || ! is_callable([$user, 'hasPermission'])) {
            return false;
        }

        return (bool) call_user_func([$user, 'hasPermission'], 'stats');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Évaluation')
                    ->description('Note et commentaire')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Utilisateur')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->disabledOn('edit')
                            ->required(),

                        Forms\Components\Radio::make('stars')
                            ->label('Note')
                            ->options([
                                1 => '⭐ 1 étoile',
                                2 => '⭐⭐ 2 étoiles',
                                3 => '⭐⭐⭐ 3 étoiles',
                                4 => '⭐⭐⭐⭐ 4 étoiles',
                                5 => '⭐⭐⭐⭐⭐ 5 étoiles',
                            ])
                            ->disabledOn('edit')
                            ->required(),

                        Forms\Components\Textarea::make('comment')
                            ->label('Commentaire')
                            ->disabledOn('edit')
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_testimonial')
                            ->label('Afficher sur la page d\'accueil (Témoignage)')
                            ->inline(false),

                        Forms\Components\DateTimePicker::make('created_at')
                            ->label('Évalué le')
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ressource.titre')
                    ->label('Ressource')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Utilisateur')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('stars')
                    ->label('Note')
                    ->formatStateUsing(fn (int $state): string => str_repeat('⭐', $state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('comment')
                    ->label('Commentaire')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_testimonial')
                    ->label('Témoignage')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('stars')
                    ->label('Filtrer par note')
                    ->options([
                        1 => '1 étoile',
                        2 => '2 étoiles',
                        3 => '3 étoiles',
                        4 => '4 étoiles',
                        5 => '5 étoiles',
                    ]),
                Tables\Filters\TernaryFilter::make('is_testimonial')
                    ->label('Témoignage'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResourceRatings::route('/'),
            'create' => Pages\CreateResourceRating::route('/create'),
            'edit' => Pages\EditResourceRating::route('/{record}/edit'),
        ];
    }
}
