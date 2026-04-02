<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    // Icône d'étoile pour le menu
    protected static ?string $navigationIcon = 'heroicon-o-star';
    
    // Titre dans le menu
    protected static ?string $navigationLabel = 'Avis Visiteurs';
    
    // Ordre dans le menu (pour le mettre vers la fin)
    protected static ?int $navigationSort = 50;

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermission('stats') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nom')
                    ->label('Auteur')
                    ->readOnly(), // On ne modifie pas l'auteur

                Forms\Components\TextInput::make('note')
                    ->label('Note / 5')
                    ->readOnly(), // On ne modifie pas la note

                Forms\Components\Textarea::make('message')
                    ->label('Commentaire')
                    ->rows(5)
                    ->columnSpanFull()
                    ->readOnly(), // On ne modifie pas le message
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom')
                    ->label('Étudiant / Visiteur')
                    ->searchable()
                    ->weight('bold'),

                // Astuce Pro : Transformer le chiffre 5 en ⭐⭐⭐⭐⭐
                Tables\Columns\TextColumn::make('note')
                    ->label('Note')
                    ->formatStateUsing(fn (string $state): string => str_repeat('⭐', $state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('message')
                    ->label('Avis')
                    ->limit(50) // On coupe si c'est trop long
                    ->tooltip(fn (Review $record): string => $record->message), // Affiche tout au survol

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Reçu le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc') // Les plus récents en premier
            ->filters([
                // Tu pourrais filtrer par note ici si tu voulais
            ])
            ->actions([
                // Pas de bouton "Edit", seulement "Voir" et "Supprimer"
                Tables\Actions\ViewAction::make(), 
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
            'index' => Pages\ListReviews::route('/'),
            // On désactive la création manuelle, car les avis viennent du site public
            // 'create' => Pages\CreateReview::route('/create'),
            // 'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
    
    // Désactiver le bouton "Nouvel avis" dans l'admin
    public static function canCreate(): bool
    {
       return false;
    }
}