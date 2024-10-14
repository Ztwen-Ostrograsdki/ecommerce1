<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class AddressRelationManager extends RelationManager
{
    protected static string $relationship = 'address';

    protected static ?string $label = 'Addresse de reception';

    protected static ?string $navigationLabel = 'Addresse de reception';

    protected static ?string $title = 'Addresses de livraison indiquées';




    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make("Informations sur l'addresse de livraison")->schema([
                        TextInput::make('first_name')
                        ->required()
                        ->maxLength(255)
                        ->label('Nom du receveur'),

                        TextInput::make('last_name')
                        ->required()
                        ->maxLength(255)
                        ->label('Prénoms du receveur'),

                        TextInput::make('phone')
                        ->required()
                        ->maxLength(255)
                        ->tel()
                        ->label('Contacts'),

                        TextInput::make('city')
                        ->required()
                        ->maxLength(255)
                        ->label('Ville'),

                        TextInput::make('state')
                        ->required()
                        ->maxLength(255)
                        ->label('Etat ou Commune'),

                        TextInput::make('zip_code')
                        ->required()
                        ->numeric()
                        ->maxLength(10)
                        ->label('Code ZIP ou Postal'),

                        Textarea::make('street_address')
                            ->required()
                            ->columnSpanFull()
                            ->label("Adresse de la ville")
                            ->placeholder("Veuillez renseigner des infos complémentaires"),
                        
                    ])->columns(2),

                    Section::make('Images Associées: Ce sont des images qui sont à titre indicatifs')->schema([
                        FileUpload::make('image')
                                   ->label('Image associée')
                                   ->directory('addresses')

                    ])->columnSpanFull()

                ])->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('street_address')
            ->columns([
                TextColumn::make('street_address')
                    ->label("Adresse"),

                TextColumn::make('fullname')
                    ->label("Récepeteur"),
                
                TextColumn::make('phone')
                    ->label("Contacts"),

                TextColumn::make('city')
                    ->label("Ville"),

                TextColumn::make('state')
                    ->label("Etat ou Commune"),

                TextColumn::make('zip_code')
                    ->label("Code ZIP ou Postal"),

                TextColumn::make('street_address')
                    ->label("Addresse"),
                    

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                
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
}
