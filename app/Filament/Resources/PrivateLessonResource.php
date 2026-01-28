<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrivateLessonResource\Pages;
use App\Models\PrivateLesson;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\BadgeColumn;

class PrivateLessonResource extends Resource
{
    protected static ?string $model = PrivateLesson::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Cours Particuliers';

    protected static ?string $navigationGroup = 'Académique';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations générales')
                    ->description('Détails du cours particulier')
                    ->schema([
                        Forms\Components\TextInput::make('titre')
                            ->label('Titre du cours')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('teacher_id')
                            ->label('Professeur')
                            ->relationship('teacher', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('matiere_id')
                            ->label('Matière')
                            ->relationship('matiere', 'nom')
                            ->searchable()
                            ->preload(),
                    ])->columns(2),

                Forms\Components\Section::make('Tarification et durée')
                    ->description('Informations commerciales')
                    ->schema([
                        Forms\Components\TextInput::make('prix')
                            ->label('Prix (FCFA)')
                            ->numeric()
                            ->required()
                            ->prefix('FCFA'),

                        Forms\Components\TextInput::make('duree_minutes')
                            ->label('Durée (minutes)')
                            ->numeric()
                            ->required()
                            ->suffix('min'),

                        Forms\Components\TextInput::make('places_max')
                            ->label('Nombre maximum de places')
                            ->numeric()
                            ->required()
                            ->default(1),
                    ])->columns(3),

                Forms\Components\Section::make('Disponibilités et statut')
                    ->description('Organiser les horaires')
                    ->schema([
                        Forms\Components\Textarea::make('disponibilites')
                            ->label('Disponibilités (JSON)')
                            ->helperText('Format: [{"jour":"Lundi","horaires":"14h-16h"}]')
                            ->required()
                            ->columnSpanFull()
                            ->rows(5),

                        Forms\Components\Select::make('statut')
                            ->label('Statut')
                            ->options([
                                'actif' => 'Actif',
                                'inactif' => 'Inactif',
                                'complet' => 'Complet',
                            ])
                            ->default('actif')
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('titre')
                    ->label('Titre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Professeur')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('matiere.nom')
                    ->label('Matière')
                    ->searchable(),

                Tables\Columns\TextColumn::make('prix')
                    ->label('Prix')
                    ->formatStateUsing(fn ($state) => number_format($state, 0) . ' FCFA')
                    ->sortable(),

                Tables\Columns\TextColumn::make('duree_minutes')
                    ->label('Durée')
                    ->formatStateUsing(fn ($state) => $state . ' min')
                    ->sortable(),

                Tables\Columns\TextColumn::make('places_max')
                    ->label('Places')
                    ->sortable(),

                BadgeColumn::make('statut')
                    ->label('Statut')
                    ->colors([
                        'success' => 'actif',
                        'danger' => 'inactif',
                        'warning' => 'complet',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('statut')
                    ->label('Filtrer par statut')
                    ->options([
                        'actif' => 'Actif',
                        'inactif' => 'Inactif',
                        'complet' => 'Complet',
                    ]),
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
            'index' => Pages\ListPrivateLessons::route('/'),
            'create' => Pages\CreatePrivateLesson::route('/create'),
            'edit' => Pages\EditPrivateLesson::route('/{record}/edit'),
        ];
    }
}
