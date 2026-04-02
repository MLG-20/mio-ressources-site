<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section; // Pour organiser en blocs

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    // Une icône plus parlante (Photo)
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    
    // On change le nom dans le menu
    protected static ?string $navigationLabel = 'Slider Accueil';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermission('sliders') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Visuel du Slider')
                    ->description('L\'image principale qui sera affichée sur l\'accueil.')
                    ->schema([
                        Forms\Components\FileUpload::make('image_path')
                            ->label('Image (Format large conseillé)')
                            ->image()
                            ->disk('public')
                            ->directory('sliders')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                null,        // Permet le recadrage libre
                                '16:9',      // Format cinéma / slider
                                '4:3',       // Format standard
                                '1:1',       // Format carré
                            ])
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Informations Textuelles')
                    ->schema([
                        Forms\Components\TextInput::make('titre')
                            ->label('Titre (Optionnel)')
                            ->placeholder('Ex: Bienvenue sur MIO Ressources')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('ordre')
                            ->label('Position')
                            ->helperText('1 pour le premier, 2 pour le second, etc.')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\Textarea::make('description')
                            ->label('Texte d\'accroche (Petit texte)')
                            ->placeholder('Ex: Accédez à l\'intégralité des ressources...')
                            ->columnSpanFull()
                            ->required(),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Aperçu')
                    ->square(), // Ou circular() si tu préfères
                Tables\Columns\TextColumn::make('titre')
                    ->label('Titre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ordre')
                    ->label('Ordre')
                    ->sortable()
                    ->badge(), // Affiche l'ordre dans une petite pastille
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('ordre', 'asc') // Trie par défaut par ordre
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(), // Ajout de la suppression directe
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
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}