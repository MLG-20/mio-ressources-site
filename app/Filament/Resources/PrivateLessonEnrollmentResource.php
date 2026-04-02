<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrivateLessonEnrollmentResource\Pages;
use App\Models\PrivateLessonEnrollment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\BadgeColumn;

class PrivateLessonEnrollmentResource extends Resource
{
    protected static ?string $model = PrivateLessonEnrollment::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Inscriptions Cours';

    protected static ?string $navigationGroup = 'Académique';

    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermission('cours') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Inscription au cours')
                    ->description('Gérer les inscriptions aux cours particuliers')
                    ->schema([
                        Forms\Components\Select::make('private_lesson_id')
                            ->label('Cours particulier')
                            ->relationship('privateLesson', 'titre')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('student_id')
                            ->label('Étudiant')
                            ->relationship('student', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\DateTimePicker::make('enrolled_at')
                            ->label('Inscrit le')
                            ->default(now()),

                        Forms\Components\Select::make('status')
                            ->label('Statut')
                            ->options([
                                'active' => 'Actif',
                                'completed' => 'Terminé',
                                'cancelled' => 'Annulé',
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
                Tables\Columns\TextColumn::make('privateLesson.titre')
                    ->label('Cours')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('student.name')
                    ->label('Étudiant')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('privateLesson.teacher.name')
                    ->label('Professeur')
                    ->searchable(),

                Tables\Columns\TextColumn::make('enrolled_at')
                    ->label('Inscrit le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Statut')
                    ->colors([
                        'success' => 'active',
                        'info' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'active' => 'Actif',
                        'completed' => 'Terminé',
                        'cancelled' => 'Annulé',
                        default => $state,
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filtrer par statut')
                    ->options([
                        'active' => 'Actif',
                        'completed' => 'Terminé',
                        'cancelled' => 'Annulé',
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
            'index' => Pages\ListPrivateLessonEnrollments::route('/'),
            'create' => Pages\CreatePrivateLessonEnrollment::route('/create'),
            'edit' => Pages\EditPrivateLessonEnrollment::route('/{record}/edit'),
        ];
    }
}
