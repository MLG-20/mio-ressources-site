<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'Utilisateurs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations de base')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nom complet')
                            ->required(),
                        
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('role')
                            ->label('Rôle Permission')
                            ->options([
                                'admin' => 'Administrateur',
                                'professeur' => 'Professeur',
                                'etudiant' => 'Étudiant',
                            ])
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Statut Académique')
                    ->description('Définissez le type de compte et le niveau d\'étude.')
                    ->schema([
                        Forms\Components\Select::make('user_type')
                            ->label('Type d\'utilisateur')
                            ->options([
                                'student' => 'Étudiant',
                                'teacher' => 'Professeur',
                                'admin' => 'Staff Administratif',
                            ])
                            ->live() // Permet de réagir en direct
                            ->required(),

                        Forms\Components\Select::make('student_level')
                            ->label('Niveau d\'étude')
                            ->options([
                                'L1' => 'Licence 1',
                                'L2' => 'Licence 2',
                                'L3' => 'Licence 3',
                            ])
                            // Ce champ ne s'affiche QUE si on a choisi "Étudiant" au dessus
                            ->hidden(fn (Get $get): bool => $get('user_type') !== 'student')
                            ->required(fn (Get $get): bool => $get('user_type') === 'student'),
                    ])->columns(2),

                Forms\Components\Section::make('Sécurité')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->label('Mot de passe')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create'),
                    ]),
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
                
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('user_type')
                    ->label('Type')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('student_level')
                    ->label('Niveau')
                    ->badge()
                    ->color('info')
                    // ON CACHE SI C'EST PAS UN ÉTUDIANT
                    ->formatStateUsing(fn ($state, $record) => $record->user_type === 'student' ? $state : '—')
                    ->color(fn ($state, $record) => $record->user_type === 'student' ? 'info' : 'gray'),

                Tables\Columns\TextColumn::make('role')
                    ->label('Rôle')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'professeur' => 'warning',
                        'etudiant' => 'success',
                        default => 'gray',
                    })
                    ->icon(fn ($record) => $record->id === 1 ? 'heroicon-s-star' : null)
                    ->formatStateUsing(fn ($state, $record) => $record->id === 1 ? 'Super Admin' : ucfirst($state)),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Inscrit le')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Optionnel : Ajouter un filtre par niveau
                Tables\Filters\SelectFilter::make('student_level')
                    ->label('Filtrer par Niveau')
                    ->options([
                        'L1' => 'Licence 1',
                        'L2' => 'Licence 2',
                        'L3' => 'Licence 3',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(fn ($record) => $record->id === 1 && auth()->id() !== 1),
                
                Tables\Actions\DeleteAction::make()
                    ->hidden(fn ($record) => $record->id === 1), 
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}