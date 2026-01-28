<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkGroupResource\Pages;
use App\Models\WorkGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\BadgeColumn;

class WorkGroupResource extends Resource
{
    protected static ?string $model = WorkGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Groupes de Travail';

    protected static ?string $navigationGroup = 'Communauté';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations du groupe')
                    ->description('Détails du groupe de travail')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nom du groupe')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('creator_id')
                            ->label('Créateur')
                            ->relationship('creator', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Statut')
                            ->options([
                                'active' => 'Actif',
                                'inactive' => 'Inactif',
                                'archived' => 'Archivé',
                            ])
                            ->default('active')
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Créateur')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('members_count')
                    ->label('Membres')
                    ->counts('members')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Statut')
                    ->colors([
                        'success' => 'active',
                        'gray' => 'inactive',
                        'warning' => 'archived',
                    ])
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'active' => 'Actif',
                        'inactive' => 'Inactif',
                        'archived' => 'Archivé',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filtrer par statut')
                    ->options([
                        'active' => 'Actif',
                        'inactive' => 'Inactif',
                        'archived' => 'Archivé',
                    ]),
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
            'index' => Pages\ListWorkGroups::route('/'),
            'create' => Pages\CreateWorkGroup::route('/create'),
            'edit' => Pages\EditWorkGroup::route('/{record}/edit'),
        ];
    }
}
