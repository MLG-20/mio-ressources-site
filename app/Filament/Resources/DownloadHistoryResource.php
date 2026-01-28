<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DownloadHistoryResource\Pages;
use App\Models\DownloadHistory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DownloadHistoryResource extends Resource
{
    protected static ?string $model = DownloadHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected static ?string $navigationLabel = 'Historique Téléchargements';

    protected static ?string $navigationGroup = 'Statistiques';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Téléchargement')
                    ->description('Informations du téléchargement')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Utilisateur')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('file_name')
                            ->label('Nom du fichier')
                            ->disabled()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('file_path')
                            ->label('Chemin du fichier')
                            ->disabled()
                            ->columnSpanFull(),

                        Forms\Components\DateTimePicker::make('downloaded_at')
                            ->label('Téléchargé le')
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Utilisateur')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('file_name')
                    ->label('Fichier')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('downloaded_at')
                    ->label('Téléchargé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('De'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('À'),
                    ])
                    ->query(function ($query, array $data) {
                        return $data['created_from']
                            ? $query->whereBetween('downloaded_at', [$data['created_from'], $data['created_until'] ?? now()])
                            : $query;
                    }),
            ])
            ->actions([
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
            'index' => Pages\ListDownloadHistories::route('/'),
        ];
    }
}
