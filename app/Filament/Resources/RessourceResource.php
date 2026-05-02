<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RessourceResource\Pages;
use App\Models\Ressource;
use App\Models\Semestre;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Get;
use Filament\Forms\Components\Section;

class RessourceResource extends Resource
{
    protected static ?string $model = Ressource::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static ?string $navigationLabel = 'Ressources Académiques';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermission('ressources') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informations de la ressource')
                    ->schema([
                        TextInput::make('titre')
                            ->label('Titre du document')
                            ->required()
                            ->maxLength(255),

                        Select::make('matiere_id')
                            ->label('Matière')
                            ->relationship('matiere', 'nom')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('type')
                            ->label('Type de contenu')
                            ->options([
                                'Cours' => '📚 Cours PDF',
                                'TD'    => '📝 Travaux Dirigés',
                                'Vidéo' => '🎥 Lien Vidéo',
                                'Autre' => '📁 Autre document',
                            ])
                            ->required()
                            ->live(),

                        FileUpload::make('file_path')
                            ->label('Téléverser le fichier (PDF, DOC, ZIP, etc.)')
                            ->directory('ressources')
                            ->maxSize(51200)
                            ->required()
                            ->visible(fn (Get $get): bool => $get('type') !== 'Vidéo'),

                        TextInput::make('file_path')
                            ->label('Lien de la vidéo (YouTube / Drive)')
                            ->url()
                            ->placeholder('https://www.youtube.com/watch?v=...')
                            ->required()
                            ->visible(fn (Get $get): bool => $get('type') === 'Vidéo'),
                    ])->columns(2),

                Section::make('Options Premium')
                    ->schema([
                        Forms\Components\Toggle::make('is_premium')
                            ->label('Contenu Payant')
                            ->onColor('warning')
                            ->live(),

                        TextInput::make('price')
                            ->label('Prix (CFA)')
                            ->numeric()
                            ->prefix('CFA')
                            ->visible(fn (Get $get): bool => $get('is_premium'))
                            ->required(fn (Get $get): bool => $get('is_premium')),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $isSuperAdmin = auth()->user()?->isSuperAdmin() ?? false;

        return $table
            ->columns([
                TextColumn::make('titre')
                    ->label('Titre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Cours' => 'info',
                        'TD'    => 'gray',
                        'Vidéo' => 'danger',
                        default => 'slate',
                    }),

                TextColumn::make('matiere.semestre.niveau')
                    ->label('Niveau')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'L1' => 'info',
                        'L2' => 'warning',
                        'L3' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('matiere.semestre.nom')
                    ->label('Semestre')
                    ->sortable()
                    ->limit(20),

                TextColumn::make('matiere.nom')
                    ->label('Matière')
                    ->searchable()
                    ->limit(20),

                TextColumn::make('price')
                    ->label('Accès')
                    ->alignCenter()
                    ->sortable()
                    ->formatStateUsing(fn ($record) => $record->is_premium ? $record->price . ' CFA' : 'Gratuit')
                    ->badge()
                    ->color(fn ($record): string => $record->is_premium ? 'warning' : 'success')
                    ->icon(fn ($record): string => $record->is_premium ? 'heroicon-m-star' : 'heroicon-m-check-badge'),

                TextColumn::make('user.name')
                    ->label('Ajouté par')
                    ->sortable()
                    ->badge()
                    ->color('primary')
                    ->visible($isSuperAdmin),

                TextColumn::make('created_at')
                    ->label('Ajouté le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'Cours' => '📚 Cours',
                        'TD'    => '📝 TD',
                        'Vidéo' => '🎥 Vidéo',
                        'Autre' => '📁 Autre',
                    ]),

                Tables\Filters\SelectFilter::make('niveau')
                    ->label('Niveau')
                    ->options(['L1' => 'Licence 1', 'L2' => 'Licence 2', 'L3' => 'Licence 3'])
                    ->query(fn ($query, array $data) => $query->when(
                        $data['value'],
                        fn ($q, $v) => $q->whereHas('matiere.semestre', fn ($s) => $s->where('niveau', $v))
                    )),

                Tables\Filters\SelectFilter::make('semestre')
                    ->label('Semestre')
                    ->options(fn () => Semestre::orderBy('niveau')->orderBy('nom')->pluck('nom', 'id'))
                    ->query(fn ($query, array $data) => $query->when(
                        $data['value'],
                        fn ($q, $v) => $q->whereHas('matiere', fn ($m) => $m->where('semestre_id', $v))
                    )),

                Tables\Filters\TernaryFilter::make('is_premium')
                    ->label('Accès')
                    ->placeholder('Tous les documents')
                    ->trueLabel('Documents Premium')
                    ->falseLabel('Documents Gratuits'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRessources::route('/'),
            'create' => Pages\CreateRessource::route('/create'),
            'edit'   => Pages\EditRessource::route('/{record}/edit'),
        ];
    }
}
