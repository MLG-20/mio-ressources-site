<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ForumSujetResource\Pages;
use App\Filament\Resources\ForumSujetResource\RelationManagers;
use App\Models\ForumSujet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ForumSujetResource extends Resource
{
    protected static ?string $model = ForumSujet::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermission('forum') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('titre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('forum_category_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('nombre_vues')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('est_epingle')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('titre')->searchable(),
            Tables\Columns\TextColumn::make('user.name')->label('Auteur'),
            Tables\Columns\TextColumn::make('category.nom')->badge(),
            // Un interrupteur (toggle) pour épingler un sujet sans ouvrir de page
            Tables\Columns\ToggleColumn::make('est_epingle')
                ->label('Épinglé'),
            Tables\Columns\TextColumn::make('created_at')->label('Posté le')->dateTime(),
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
            'index' => Pages\ListForumSujets::route('/'),
            'create' => Pages\CreateForumSujet::route('/create'),
            'edit' => Pages\EditForumSujet::route('/{record}/edit'),
        ];
    }
}
