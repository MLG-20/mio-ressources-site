<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MeetingResource\Pages;
use App\Models\Meeting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\BadgeColumn;

class MeetingResource extends Resource
{
    protected static ?string $model = Meeting::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationLabel = 'Réunions';

    protected static ?string $navigationGroup = 'Académique';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations de la réunion')
                    ->description('Configurez les détails de la réunion')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Titre')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('room_name')
                            ->label('Nom de la salle (code)')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Identifiant unique de la salle de réunion'),

                        Forms\Components\Select::make('user_id')
                            ->label('Animateur (Enseignant)')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\DateTimePicker::make('scheduled_at')
                            ->label('Programmée pour')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Statut')
                    ->description('État actuelle de la réunion')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Statut')
                            ->options([
                                'scheduled' => 'Programmée',
                                'in_progress' => 'En cours',
                                'completed' => 'Terminée',
                                'cancelled' => 'Annulée',
                            ])
                            ->default('scheduled')
                            ->required(),

                        Forms\Components\DateTimePicker::make('started_at')
                            ->label('Commencée à'),

                        Forms\Components\DateTimePicker::make('closed_at')
                            ->label('Terminée à'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Titre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Animateur')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('room_name')
                    ->label('Salle')
                    ->searchable(),

                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label('Programmée')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Statut')
                    ->colors([
                        'info' => 'scheduled',
                        'warning' => 'in_progress',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'scheduled' => 'Programmée',
                        'in_progress' => 'En cours',
                        'completed' => 'Terminée',
                        'cancelled' => 'Annulée',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('enrolledStudents_count')
                    ->label('Participants')
                    ->counts('enrolledStudents'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filtrer par statut')
                    ->options([
                        'scheduled' => 'Programmée',
                        'in_progress' => 'En cours',
                        'completed' => 'Terminée',
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
            'index' => Pages\ListMeetings::route('/'),
            'create' => Pages\CreateMeeting::route('/create'),
            'edit' => Pages\EditMeeting::route('/{record}/edit'),
        ];
    }
}
