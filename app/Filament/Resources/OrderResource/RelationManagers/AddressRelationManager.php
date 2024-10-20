<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                        FileUpload::make('images')
                                   ->label('Images de reférence')
                                   ->directory('addresses')
                                   ->multiple()
                                   ->required()
                                   ->reorderable()
                                   ->maxFiles(3)

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

                ImageColumn::make('images')
                    ->label("Images pour localiser"),
                    

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
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
