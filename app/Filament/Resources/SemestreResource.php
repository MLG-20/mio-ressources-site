<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SemestreResource\Pages;
use App\Filament\Resources\SemestreResource\RelationManagers;
use App\Models\Semestre;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;

class SemestreResource extends Resource
{
    protected static ?string $model = Semestre::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Section::make('Détails du Semestre')
                ->schema([
                    Forms\Components\TextInput::make('nom')->required(),
                    Forms\Components\TextInput::make('niveau')->required()->placeholder('Ex: L1'),
                ])->columns(2),

            Section::make('Design de la Carte')
                ->description('L\'image qui apparaîtra en fond de carte sur la page d\'accueil.')
                ->schema([
                    FileUpload::make('image_path')
                        ->label('Image de fond')
                        ->image()
                        ->directory('semestres')
                        ->imageEditor() // Pour que tu puisses la recadrer parfaitement
                        ->required(),
                ]),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('niveau')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSemestres::route('/'),
            'create' => Pages\CreateSemestre::route('/create'),
            'edit' => Pages\EditSemestre::route('/{record}/edit'),
        ];
    }
}
