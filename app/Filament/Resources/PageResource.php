<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('titre')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, $set) => $set('slug', str()->slug($state))),
            TextInput::make('slug')->required()->unique(ignoreRecord: true),

            // Image vedette optionnelle
            Forms\Components\FileUpload::make('cover_image')
                ->label('Image vedette (optionnelle)')
                ->image()
                ->directory('pages-covers')
                ->imageEditor()
                ->imageEditorAspectRatios(['16:9', '4:3', '1:1'])
                ->helperText('📸 Ajoutez une image en vedette qui s\'affichera en haut de la page. Cette image est complètement optionnelle.')
                ->columnSpanFull(),

            // L'éditeur pro pour le contenu
            RichEditor::make('contenu')
                ->label('Contenu de la page')
                ->fileAttachmentsDirectory('pages-content')
                ->fileAttachmentsVisibility('public')
                ->helperText('💡 Astuce: Pour insérer des images dans le contenu, utilisez le bouton image de l\'éditeur.')
                ->columnSpanFull()
                ->required(),
        ]);
}
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('titre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
