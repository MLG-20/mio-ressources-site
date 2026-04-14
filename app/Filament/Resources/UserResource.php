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
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Utilisateurs';

    public static function canViewAny(): bool
    {
        $user = Auth::user();

        return $user && is_callable([$user, 'hasPermission'])
            ? (bool) call_user_func([$user, 'hasPermission'], 'users')
            : false;
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $adminEmail = env('ADMIN_EMAIL');

        return parent::getEloquentQuery()
            ->where(function ($query) use ($adminEmail) {
                $query->where('role', '!=', 'admin')
                      ->orWhere(function ($q) use ($adminEmail) {
                          // Garder les sous-admins (role=admin mais pas le super admin)
                          $q->where('role', 'admin')
                            ->where('email', '!=', $adminEmail);
                      });
            });
    }

    public static function form(Form $form): Form
    {
        $authUser = Auth::user();
        $isSuperAdmin = $authUser && is_callable([$authUser, 'isSuperAdmin'])
            ? (bool) call_user_func([$authUser, 'isSuperAdmin'])
            : false;

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
                            ->label('Rôle')
                            ->options([
                                'admin'     => 'Sous-Admin',
                                'professeur' => 'Professeur',
                                'etudiant'  => 'Étudiant',
                            ])
                            ->required()
                            ->live()
                            ->disabled(fn ($record) => $record?->isSuperAdmin()),

                        Forms\Components\TextInput::make('password')
                            ->label('Mot de passe')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create'),
                    ])->columns(2),

                Forms\Components\Section::make('Statut Académique')
                    ->schema([
                        Forms\Components\Select::make('user_type')
                            ->label('Type d\'utilisateur')
                            ->options([
                                'student' => 'Étudiant',
                                'teacher' => 'Professeur',
                                'admin'   => 'Staff Administratif',
                            ])
                            ->live()
                            ->required(),

                        Forms\Components\Select::make('student_level')
                            ->label('Niveau d\'étude')
                            ->options([
                                'L1' => 'Licence 1',
                                'L2' => 'Licence 2',
                                'L3' => 'Licence 3',
                            ])
                            ->hidden(fn (Get $get): bool => $get('user_type') !== 'student')
                            ->required(fn (Get $get): bool => $get('user_type') === 'student'),

                        Forms\Components\DateTimePicker::make('trial_ends_at')
                            ->label('Fin essai gratuit')
                            ->seconds(false)
                            ->hidden(fn (Get $get): bool => $get('user_type') !== 'student'),

                        Forms\Components\DateTimePicker::make('subscription_paid_until')
                            ->label('Abonnement payé jusqu\'au')
                            ->seconds(false)
                            ->hidden(fn (Get $get): bool => $get('user_type') !== 'student'),
                    ])->columns(2),

                // Section permissions — visible seulement par le super admin pour les sous-admins
                Forms\Components\Section::make('Permissions du sous-admin')
                    ->description('Sélectionnez les sections auxquelles ce sous-admin peut accéder.')
                    ->schema([
                        Forms\Components\CheckboxList::make('permissions')
                            ->label('')
                            ->options([
                                'users'           => 'Gestion des utilisateurs',
                                'ressources'      => 'Ressources pédagogiques',
                                'forum'           => 'Forum',
                                'semestres'       => 'Semestres & Matières',
                                'paiements'       => 'Paiements & Achats',
                                'cours'           => 'Cours particuliers',
                                'meetings'        => 'Réunions',
                                'workgroups'      => 'Groupes de travail',
                                'publications'    => 'Publications',
                                'sliders'         => 'Sliders & Pages',
                                'settings'        => 'Paramètres',
                                'stats'           => 'Statistiques & Avis',
                            ])
                            ->columns(3)
                            ->gridDirection('row'),
                    ])
                    ->visible(fn (Get $get) => $isSuperAdmin && $get('role') === 'admin'),
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

                Tables\Columns\TextColumn::make('role')
                    ->label('Rôle')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin'     => 'danger',
                        'professeur' => 'warning',
                        'etudiant'  => 'success',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn ($state, $record) => $record->isSuperAdmin() ? 'Super Admin' : ucfirst($state)),

                Tables\Columns\TextColumn::make('student_level')
                    ->label('Niveau')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'L1' => 'blue',
                        'L2' => 'orange',
                        'L3' => 'purple',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state, $record) => $record->user_type === 'student' ? ($state ?? 'N/A') : '—'),

                Tables\Columns\TextColumn::make('subscription_paid_until')
                    ->label('Valide jusqu\'au')
                    ->dateTime('d/m/Y'),

                Tables\Columns\IconColumn::make('is_blocked')
                    ->label('Bloqué')
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-closed')
                    ->falseIcon('heroicon-o-lock-open')
                    ->trueColor('danger')
                    ->falseColor('success'),

                Tables\Columns\TextColumn::make('trial_ends_at')
                    ->label('Fin essai')
                    ->dateTime('d/m/Y')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('subscription_paid_until')
                    ->label('Abonné jusqu\'au')
                    ->dateTime('d/m/Y')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Inscrit le')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Rôle')
                    ->options([
                        'admin'     => 'Sous-Admin',
                        'professeur' => 'Professeur',
                        'etudiant'  => 'Étudiant',
                    ]),

                Tables\Filters\SelectFilter::make('student_level')
                    ->label('Niveau d\'étude')
                    ->options([
                        'L1' => 'Licence 1',
                        'L2' => 'Licence 2',
                        'L3' => 'Licence 3',
                    ]),

                Tables\Filters\Filter::make('subscription_status')
                    ->label('Statut Abonnement')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('Montrer les étudiants...')
                            ->options([
                                'actif' => '✓ Actif',
                                'essai' => '⏳ En essai',
                                'expire' => '✗ Expiré',
                            ])
                            ->required(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['status'] === 'actif',
                            fn (Builder $q) => $q->where('subscription_paid_until', '>', now())
                        )->when(
                            $data['status'] === 'essai',
                            fn (Builder $q) => $q->where('trial_ends_at', '>', now())
                                ->where(fn (Builder $q2) => $q2->whereNull('subscription_paid_until')
                                    ->orWhere('subscription_paid_until', '<=', now()))
                        )->when(
                            $data['status'] === 'expire',
                            fn (Builder $q) => $q->where(fn (Builder $q2) => $q2->whereNull('subscription_paid_until')
                                ->orWhere('subscription_paid_until', '<=', now()))
                                ->where(fn (Builder $q3) => $q3->whereNull('trial_ends_at')
                                    ->orWhere('trial_ends_at', '<=', now()))
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(fn ($record) => $record->isSuperAdmin() && ! (Auth::user() && is_callable([Auth::user(), 'isSuperAdmin']) && (bool) call_user_func([Auth::user(), 'isSuperAdmin']))),

                Tables\Actions\Action::make('toggle_block')
                    ->label(fn ($record) => $record->is_blocked ? 'Débloquer' : 'Bloquer')
                    ->icon(fn ($record) => $record->is_blocked ? 'heroicon-o-lock-open' : 'heroicon-o-lock-closed')
                    ->color(fn ($record) => $record->is_blocked ? 'success' : 'warning')
                    ->requiresConfirmation()
                    ->modalHeading(fn ($record) => $record->is_blocked ? 'Débloquer ce compte ?' : 'Bloquer ce compte ?')
                    ->modalDescription(fn ($record) => $record->is_blocked
                        ? 'Ce sous-admin pourra à nouveau accéder au panel.'
                        : 'Ce sous-admin ne pourra plus accéder au panel.')
                    ->action(function ($record) {
                        $record->update(['is_blocked' => !$record->is_blocked]);
                        Notification::make()
                            ->title($record->is_blocked ? 'Compte bloqué' : 'Compte débloqué')
                            ->success()
                            ->send();
                    })
                    ->hidden(fn ($record) => $record->isSuperAdmin() || $record->user_type === 'student')
                    ->visible(fn () => Auth::user() && is_callable([Auth::user(), 'isSuperAdmin']) && (bool) call_user_func([Auth::user(), 'isSuperAdmin'])),

                Tables\Actions\DeleteAction::make()
                    ->hidden(fn ($record) => $record->isSuperAdmin()),
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
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    /**
     * Détermine le statut d'abonnement d'un étudiant
     */
    private static function getSubscriptionStatus(User $user): string
    {
        $now = now();

        if ($user->subscription_paid_until && $user->subscription_paid_until > $now) {
            return 'Actif';
        } elseif ($user->trial_ends_at && $user->trial_ends_at > $now) {
            return 'En essai';
        } else {
            return 'Expiré';
        }
    }

    /**
     * Retourne la couleur du badge selon le statut d'abonnement
     */
    private static function getSubscriptionColor(User $user): string
    {
        $now = now();

        if ($user->subscription_paid_until && $user->subscription_paid_until > $now) {
            return 'success'; // Vert
        } elseif ($user->trial_ends_at && $user->trial_ends_at > $now) {
            return 'warning'; // Orange
        } else {
            return 'danger'; // Rouge
        }
    }
}
