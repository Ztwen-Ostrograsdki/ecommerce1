<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\OrdersRelationManager;
use App\Models\User;
use App\Notifications\SendDynamicMailToUser;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Les Utilisateurs';

    public static ?string $label = "Les Utilisateurs";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Info Personnelles')->schema([
                    TextInput::make('name')
                        ->label('Nom utilisateur ou pseudo')
                        ->required(),

                    TextInput::make('email')
                            ->label('Adress mail')
                            ->email()
                            ->maxlength('255')
                            ->unique(ignoreRecord: true)
                            ->required(),
                    
                    DateTimePicker::make('email_verified_at')
                            ->label('Email vérifié le ')
                            ->default(now()),

                    TextInput::make('password')
                            ->label('mot de passe')
                            ->password()
                            ->hiddenOn('edit')
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(Page $livewire) : bool => $livewire instanceof CreateRecord)
                    ])->columns(2),

                    Section::make('Photo de Profil')->schema([
                        FileUpload::make('profil_photo')
                        ->image()
                        ->directory('users'),
                    ]),

                ])->columnSpanFull()
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                ImageColumn::make('profil_photo')->circular(true),
                TextColumn::make('email')->searchable(),
                TextColumn::make('email_verified_at')->dateTime()->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
                TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),

                    Action::make('sendEmail')
                            ->label("Envoyer un mail ")
                            ->color('info')
                            ->icon('heroicon-o-rectangle-stack')
                            ->form([
                                TextInput::make('email')
                                            ->label("Receveur")
                                            ->default(fn (Model $user) => $user->email ?$user->email : "non renseigné")
                                            ->dehydrated()
                                            ->disabled()
                                            ->required(),
                                TextInput::make('subject')->required(),
                                RichEditor::make('body')->required(),
                            ])
                            ->action(function (Model $user, array $data) {

                                $user->notify(new SendDynamicMailToUser($data['subject'], $data['body']));
                                
                            }),

                    Action::make('markAsVerified')
                            ->label("Action sur la vérification du mail")
                            ->color(fn (Model $user) => $user->emailVerified() ? "danger" : "success")
                            ->icon( fn (Model $user) => $user->emailVerified() ? "heroicon-o-lock-closed" : "heroicon-o-lock-open")
                            ->form([
                                Checkbox::make('as_verified')
                                        ->label('Marquer: compte déjà Verifié')
                                        ->reactive()
                                        ->default(function(User $user){
                                            return  $user->emailVerified();
                                        })
                                        ->afterStateUpdated(fn ($state, Set $set) => $set('as_not_verified', $state == true ? false : true)),
                                Checkbox::make('as_not_verified')
                                        ->label('Marquer: Compte Non Verifié')
                                        ->reactive()
                                        ->default(function(User $user){
                                            return  !$user->emailVerified();
                                        })
                                        ->afterStateUpdated(fn ($state, Set $set) => $set('as_verified', $state == true ? false : true)),
                                
                            ])
                            ->action(function (Model $user, array $data) {

                                $user->markUserAsVerifiedOrNot($data['as_verified'], $data['as_not_verified']);
                                
                            })

                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('markLotAsNotVerified')
                            ->label("Marquer le comptes non vérifiés")
                            ->color('danger')
                            ->icon("heroicon-o-lock-closed")
                            ->form([
                                Checkbox::make('as_not_verified')
                                        ->label('Marquer les utilisateurs sélectionés : Compte Non Verifié')
                                        ->default(true)
                            ])
                            ->action(function (Collection $users, array $data) {

                                $users->each->markUserAsVerifiedOrNot(false, true);
                                
                            })->deselectRecordsAfterCompletion(),

                        BulkAction::make('markLotAsVerified')
                            ->label("Marquer le comptes vérifiés")
                            ->color('success')
                            ->icon("heroicon-o-lock-open")
                            ->form([
                                Checkbox::make('as_verified')
                                        ->label('Marquer les utilisateurs sélectionés : Compte Verifié')
                                        ->default(true)
                            ])
                            ->action(function (Collection $users, array $data) {

                                $users->each->markUserAsVerifiedOrNot(true, false);
                                
                            })->deselectRecordsAfterCompletion(),
                    
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            OrdersRelationManager::class,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() >= 10 ? static::getModel()::count() : '0' . static::getModel()::count();
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
