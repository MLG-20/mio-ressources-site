<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseResource\Pages;
use App\Filament\Resources\PurchaseResource\RelationManagers;
use App\Models\Purchase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            // 1. Choisir l'étudiant (Recherche par nom ou email)
            Select::make('user_id')
                ->label('Étudiant')
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
                ->required(),

            // 2. Choisir le document (Recherche par titre)
            Select::make('ressource_id')
                ->label('Ressource débloquée')
                ->relationship('ressource', 'titre')
                ->searchable()
                ->preload()
                ->required(),

            // 3. Le montant
            TextInput::make('amount')
                ->label('Montant payé')
                ->numeric()
                ->prefix('CFA')
                ->required(),

            // 4. L'identifiant de paiement (ex: code Wave ou OM)
            TextInput::make('payment_id')
                ->label('ID de Transaction (Wave / Orange Money)')
                ->placeholder('Ex: T240130.1234.C12345')
                ->columnSpanFull(),
        ]);
}

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('user.name')->label('Étudiant')->searchable(),

            // 1. DOCUMENT (Cours ou Livre)
            Tables\Columns\TextColumn::make('document')
                ->label('Document acheté')
                ->state(fn ($record) => $record->ressource->titre ?? $record->publication->titre ?? 'Document supprimé')
                ->weight('bold'),

            Tables\Columns\TextColumn::make('amount')->label('Prix payé')->money('XOF'),

            // 2. VENDEUR RÉEL (Version sécurisée)
            Tables\Columns\TextColumn::make('vendeur')
    ->label('Vendeur')
    ->state(function ($record) {
        $item = $record->ressource ?? $record->publication;
        $ownerId = $item?->user_id;

        // Si l'auteur est l'ID 1 OU si l'auteur est inconnu (NULL)
        if ($ownerId === 1 || is_null($ownerId)) {
            return 'Administration';
        }

        return $item->user?->name ?? 'Vendeur externe';
    })
    ->badge()
    ->color(fn ($state) => $state === 'Administration' ? 'success' : 'warning'),

// 3. COLONNE GAINS (Ajustée pour le 100%)
Tables\Columns\TextColumn::make('gains_admin')
    ->label('Ma partie')
    ->state(function ($record) {
        $item = $record->ressource ?? $record->publication;
        $ownerId = $item?->user_id;

        // CAS : Administration (Toi ou inconnu) -> 100% des gains
        if ($ownerId === 1 || is_null($ownerId)) {
            return number_format($record->amount, 0, ',', ' ') . ' F (100%)';
        }

        // CAS : Professeur -> 30% de commission
        return number_format($record->amount * 0.30, 0, ',', ' ') . ' F (30%)';
    })
    ->badge()
    ->color(fn ($state) => str_contains($state, '100%') ? 'success' : 'info')
    ->weight('bold'),
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
            'index' => Pages\ListPurchases::route('/'),
            'create' => Pages\CreatePurchase::route('/create'),
            'edit' => Pages\EditPurchase::route('/{record}/edit'),
        ];
    }

    /**
     * OPTIONNEL : Désactiver aussi le bouton de modification
     * Une vente effectuée ne doit jamais être modifiée (Audit Trail).
     */
    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }
}
