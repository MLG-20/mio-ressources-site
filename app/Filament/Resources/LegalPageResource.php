<?php

namespace App\Filament\Resources;

use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LegalPageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Pages Légales';

    protected static ?string $modelLabel = 'Page Légale';

    protected static ?int $navigationSort = 100;

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if (!$user) return false;

        // Admin a accès
        if ($user->is_admin) return true;

        // Sinon, vérifier la permission
        return $user->hasPermission('pages.manage') ?? false;
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        // Filtre uniquement les pages légales
        return parent::getEloquentQuery()
            ->whereIn('slug', ['cgu', 'mentions-legales', 'politique-confidentialite']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('titre')
                    ->required()
                    ->disabled(),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->disabled(),

                Forms\Components\RichEditor::make('contenu')
                    ->label('Contenu de la page')
                    ->fileAttachmentsDirectory('pages-content')
                    ->fileAttachmentsVisibility('public')
                    ->columnSpanFull()
                    ->required(),

                Forms\Components\Hidden::make('updated_at')
                    ->default(now()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('titre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->badge()
                    ->color('blue'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->label('Dernière modification'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Pas de bulk actions pour la sécurité
            ])
            ->defaultSort('slug');
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\LegalPageResource\Pages\ListLegalPages::route('/'),
            'edit' => \App\Filament\Resources\LegalPageResource\Pages\EditLegalPage::route('/{record}/edit'),
            'view' => \App\Filament\Resources\LegalPageResource\Pages\ViewLegalPage::route('/{record}'),
        ];
    }
}
