<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ForumMessageResource\Pages;
use App\Models\ForumMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\BadgeColumn;

class ForumMessageResource extends Resource
{
    protected static ?string $model = ForumMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left';

    protected static ?string $navigationLabel = 'Messages Forum';

    protected static ?string $navigationGroup = 'Communauté';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Message du forum')
                    ->description('Gérer les messages du forum')
                    ->schema([
                        Forms\Components\Select::make('forum_sujet_id')
                            ->label('Sujet')
                            ->relationship('sujet', 'titre')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('user_id')
                            ->label('Auteur')
                            ->relationship('author', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Textarea::make('contenu')
                            ->label('Contenu du message')
                            ->required()
                            ->rows(6)
                            ->columnSpanFull(),

                        Forms\Components\DateTimePicker::make('created_at')
                            ->label('Publié le')
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sujet.titre')
                    ->label('Sujet')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('author.name')
                    ->label('Auteur')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('contenu')
                    ->label('Message')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Publié le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('forum_sujet_id')
                    ->label('Filtrer par sujet')
                    ->relationship('sujet', 'titre'),
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
            'index' => Pages\ListForumMessages::route('/'),
            'create' => Pages\CreateForumMessage::route('/create'),
            'edit' => Pages\EditForumMessage::route('/{record}/edit'),
        ];
    }
}
