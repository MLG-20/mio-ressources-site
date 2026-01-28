<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinancialTransactionResource\Pages;
use App\Models\FinancialTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\BadgeColumn;

class FinancialTransactionResource extends Resource
{
    protected static ?string $model = FinancialTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Transactions';

    protected static ?string $navigationGroup = 'Finances';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Détails de la transaction')
                    ->description('Informations financières')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Utilisateur')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('type')
                            ->label('Type')
                            ->options([
                                'withdrawal' => 'Retrait',
                                'deposit' => 'Dépôt',
                                'purchase' => 'Achat',
                                'sale' => 'Vente',
                                'refund' => 'Remboursement',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('amount')
                            ->label('Montant (FCFA)')
                            ->numeric()
                            ->required()
                            ->prefix('FCFA'),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('status')
                            ->label('Statut')
                            ->options([
                                'pending' => 'En attente',
                                'completed' => 'Complétée',
                                'failed' => 'Échouée',
                                'cancelled' => 'Annulée',
                            ])
                            ->default('completed')
                            ->required(),

                        Forms\Components\DateTimePicker::make('transaction_date')
                            ->label('Date')
                            ->default(now()),
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

                BadgeColumn::make('type')
                    ->label('Type')
                    ->colors([
                        'info' => 'withdrawal',
                        'success' => 'deposit',
                        'warning' => 'purchase',
                        'gray' => 'sale',
                        'danger' => 'refund',
                    ])
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'withdrawal' => 'Retrait',
                        'deposit' => 'Dépôt',
                        'purchase' => 'Achat',
                        'sale' => 'Vente',
                        'refund' => 'Remboursement',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Montant')
                    ->formatStateUsing(fn ($state) => number_format($state, 0) . ' FCFA')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Statut')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                        'danger' => 'failed',
                        'gray' => 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'pending' => 'En attente',
                        'completed' => 'Complétée',
                        'failed' => 'Échouée',
                        'cancelled' => 'Annulée',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Filtrer par type')
                    ->options([
                        'withdrawal' => 'Retrait',
                        'deposit' => 'Dépôt',
                        'purchase' => 'Achat',
                        'sale' => 'Vente',
                        'refund' => 'Remboursement',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Filtrer par statut')
                    ->options([
                        'pending' => 'En attente',
                        'completed' => 'Complétée',
                        'failed' => 'Échouée',
                        'cancelled' => 'Annulée',
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
            'index' => Pages\ListFinancialTransactions::route('/'),
            'create' => Pages\CreateFinancialTransaction::route('/create'),
            'edit' => Pages\EditFinancialTransaction::route('/{record}/edit'),
        ];
    }
}
