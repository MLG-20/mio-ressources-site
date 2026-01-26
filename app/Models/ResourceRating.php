<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Filament\Resources\ResourceRatingResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ResourceRating extends Model
{
    use HasFactory;
    
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'Notes & Avis';
    protected static ?int $navigationSort = 60; // Tout en bas

    protected $fillable = ['user_id', 'ressource_id', 'publication_id', 'stars', 'comment'];

    public function user() { return $this->belongsTo(User::class); }
    public function ressource() { return $this->belongsTo(Ressource::class); }
    public function publication() { return $this->belongsTo(Publication::class); }
    
    public static function table(Table $table): Table
{
    return $table
        ->columns([
            // L'étudiant
            Tables\Columns\TextColumn::make('user.name')
                ->label('Étudiant')
                ->searchable()
                ->sortable(),

            // Le document noté
            Tables\Columns\TextColumn::make('item_name')
                ->label('Document')
                ->state(function ($record) {
                    return $record->ressource->titre ?? $record->publication->titre ?? 'Inconnu';
                }),

            // La note en étoiles
            Tables\Columns\TextColumn::make('stars')
                ->label('Note')
                ->formatStateUsing(fn (string $state): string => str_repeat('⭐', $state))
                ->sortable(),

            // Le commentaire
            Tables\Columns\TextColumn::make('comment')
                ->label('Avis')
                ->limit(50),

            Tables\Columns\TextColumn::make('created_at')
                ->dateTime('d/m/Y')
                ->label('Date'),
        ])
        ->filters([
            // Tu peux filtrer par note (ex: voir les avis 1 étoile pour gérer les problèmes)
            Tables\Filters\SelectFilter::make('stars')
                ->options([
                    5 => '⭐⭐⭐⭐⭐',
                    4 => '⭐⭐⭐⭐',
                    3 => '⭐⭐⭐',
                    2 => '⭐⭐',
                    1 => '⭐',
                ]),
        ])
        ->actions([
            Tables\Actions\DeleteAction::make(), // Tu peux supprimer un avis inapproprié
        ]);
}

}