<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MatiereResource\Pages;
use App\Models\Matiere;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MatiereResource extends Resource
{
    protected static ?string $model = Matiere::class;

    // Changement de l'icône pour quelque chose de plus académique
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    
    protected static ?string $navigationLabel = 'Matières';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermission('semestres') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations de la Matière')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Code Matière')
                            ->placeholder('ex: MIO 1111')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('nom')
                            ->label('Nom de la Matière')
                            ->placeholder('ex: Algorithmique')
                            ->required()
                            ->maxLength(255),

                        // Correction ici : On utilise Select avec relationship 
                        // pour avoir une liste déroulante au lieu d'un nombre
                        Forms\Components\Select::make('semestre_id')
                            ->label('Semestre associé')
                            ->relationship('semestre', 'nom')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('nom')
                    ->label('Nom de la matière')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('semestre.nom')
                    ->label('Semestre')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('semestre.niveau')
                    ->label('Niveau')
                    ->sortable(),
            ])
            ->filters([
                // Filtre rapide pour choisir un semestre
                Tables\Filters\SelectFilter::make('semestre')
                    ->relationship('semestre', 'nom')
                    ->label('Filtrer par Semestre'),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMatieres::route('/'),
            'create' => Pages\CreateMatiere::route('/create'),
            'edit' => Pages\EditMatiere::route('/{record}/edit'),
        ];
    }
}