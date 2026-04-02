<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get; // Import indispensable
use Filament\Forms\Components\Section;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Paramètres';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermission('settings') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Configuration du paramètre')
                    ->description('Définissez ici les réglages globaux du site.')
                    ->schema([
                        // 1. L'ÉTIQUETTE
                        Forms\Components\TextInput::make('label')
                            ->label('Étiquette (Nom pour vous)')
                            ->required(),

                        // 2. LA CLÉ (Identifiant unique)
                        Forms\Components\TextInput::make('key')
                            ->label('Clé (Identifiant interne)')
                            ->placeholder('ex: auth_bg_image')
                            ->required()
                            ->disabled(fn (string $context): bool => $context === 'edit')
                            ->live(), // Permet de changer l'affichage du champ "Value" en direct

                        // 3. LA VALEUR - CAS TEXTE OU MOT DE PASSE
                        // Ce champ est caché si on est sur le réglage de l'image ou la Google Map
                        Forms\Components\TextInput::make('value')
                            ->label('Valeur du paramètre')
                            ->placeholder('Entrez le texte ou le lien ici')
                            ->hidden(fn (Get $get) => in_array($get('key'), ['auth_bg_image', 'univ_map']))
                            ->password(fn (Get $get) => $get('key') === 'mail_password')
                            ->revealable(fn (Get $get) => $get('key') === 'mail_password')
                            ->columnSpanFull()
                            ->required(),

                        // 4. LA VALEUR - CAS UPLOAD D'IMAGE
                        // Ce champ n'apparaît QUE si la clé est 'auth_bg_image'
                        Forms\Components\FileUpload::make('value')
                            ->label('Téléverser l\'image de fond')
                            ->image()
                            ->directory('branding')
                            ->imageEditor()
                            ->imageEditorAspectRatios(['16:9'])
                            ->visible(fn (Get $get) => $get('key') === 'auth_bg_image')
                            ->columnSpanFull()
                            ->required(),

                        // 5. LA VALEUR - CAS GOOGLE MAP IFRAME
                        // Ce champ n'apparaît QUE si la clé est 'univ_map'
                        Forms\Components\Textarea::make('value')
                            ->label('Code d\'intégration Google Maps')
                            ->placeholder('<iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>')
                            ->helperText('📍 Collez le code d\'intégration complet de votre Google Map (iframe). Pour obtenir ce code: allez sur Google Maps > Partagez > Intégrer une carte')
                            ->rows(6)
                            ->visible(fn (Get $get) => $get('key') === 'univ_map')
                            ->columnSpanFull()
                            ->required(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Nom du réglage')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('key')
                    ->label('Clé interne')
                    ->fontFamily('mono')
                    ->color('gray'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Dernière mise à jour')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
