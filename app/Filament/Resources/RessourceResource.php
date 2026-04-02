<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RessourceResource\Pages;
use App\Models\Ressource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Get;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;


class RessourceResource extends Resource
{
    protected static ?string $model = Ressource::class;

    // Icône du menu (on met un dossier pour les ressources)
    protected static ?string $navigationIcon = 'heroicon-o-folder-open';

    protected static ?string $navigationLabel = 'Ressources Académiques';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermission('ressources') ?? false;
    }

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Section::make('Informations de la ressource')
                ->schema([
                    TextInput::make('titre')
                        ->label('Titre du document')
                        ->required()
                        ->maxLength(255),

                    Select::make('matiere_id')
                        ->label('Matière')
                        ->relationship('matiere', 'nom')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Select::make('type') // C'EST CE CHAMP QUE LARAVEL CHERCHAIT
                        ->label('Type de contenu')
                        ->options([
                            'Cours' => '📚 Cours PDF',
                            'TD' => '📝 Travaux Dirigés',
                            'Vidéo' => '🎥 Lien Vidéo',
                            'Autre' => '📁 Autre document',
                        ])
                        ->required()
                        ->live(), // Pour rafraîchir le formulaire dès qu'on change

                    // CHAMP DYNAMIQUE : Soit un fichier, soit un lien
                    FileUpload::make('file_path')
                        ->label('Téléverser le fichier (PDF, DOC, ZIP, etc.)')
                        ->directory('ressources')
                        ->maxSize(51200)
                        ->required()
                        ->visible(fn (Get $get): bool => $get('type') !== 'Vidéo'), // Caché si c'est une vidéo

                    TextInput::make('file_path') // On utilise le même nom de colonne !
                        ->label('Lien de la vidéo (YouTube / Drive)')
                        ->url()
                        ->placeholder('https://www.youtube.com/watch?v=...')
                        ->required()
                        ->visible(fn (Get $get): bool => $get('type') === 'Vidéo'), // Visible SEULEMENT si c'est une vidéo
                ])->columns(2),

            Forms\Components\Section::make('Options Premium')
                ->schema([
                    Forms\Components\Toggle::make('is_premium')
                        ->label('Contenu Payant')
                        ->onColor('warning')
                        ->live(),

                    TextInput::make('price')
                        ->label('Prix (CFA)')
                        ->numeric()
                        ->prefix('CFA')
                        ->visible(fn (Get $get): bool => $get('is_premium'))
                        ->required(fn (Get $get): bool => $get('is_premium')),
                ])
        ]);
}

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('titre')
                ->label('Titre')
                ->searchable()
                ->sortable()
                ->weight('bold'),

            Tables\Columns\TextColumn::make('type')
                ->badge() // Transforme le texte en pastille
                ->color(fn (string $state): string => match ($state) {
                    'Cours' => 'info',
                    'TD' => 'gray',
                    'Vidéo' => 'danger',
                    default => 'slate',
                }),

            // NOUVELLE COLONNE : STATUT FINANCIER
            Tables\Columns\TextColumn::make('price')
                ->label('Accès')
                ->alignCenter()
                ->sortable()
                ->formatStateUsing(function ($record) {
                    // Si c'est premium, on affiche le prix, sinon "Gratuit"
                    return $record->is_premium
                        ? $record->price . ' CFA'
                        : 'Gratuit';
                })
                ->badge() // Style pastille
                ->color(fn ($record): string => $record->is_premium ? 'warning' : 'success') // Jaune si payant, Vert si gratuit
                ->icon(fn ($record): string => $record->is_premium ? 'heroicon-m-star' : 'heroicon-m-check-badge'),

            Tables\Columns\TextColumn::make('matiere.nom')
                ->label('Matière')
                ->searchable()
                ->limit(20),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Ajouté le')
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            // AJOUT D'UN FILTRE POUR VOIR SEULEMENT LE PREMIUM
            Tables\Filters\TernaryFilter::make('is_premium')
                ->label('Filtrer par type d\'accès')
                ->placeholder('Tous les documents')
                ->trueLabel('Documents Premium')
                ->falseLabel('Documents Gratuits'),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ]);
}

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRessources::route('/'),
            'create' => Pages\CreateRessource::route('/create'),
            'edit' => Pages\EditRessource::route('/{record}/edit'),
        ];
    }
}
