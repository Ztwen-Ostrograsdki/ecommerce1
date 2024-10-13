<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Number;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\ToggleButtons;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = "Les demandes d'achats";

    public static ?string $label = "Demandes d'achats";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make("Details de l'achat")->schema([
                        Select::make("user_id")
                              ->label('Client')
                              ->relationship('user', 'name')
                              ->searchable()
                              ->preload()
                              ->required(),

                        Select::make('payment_method')
                              ->options([
                                'stripe' => "Stripe",
                                'cod' => "Payement en liquide",
                                'mtn' => "Mobile Money MTN",
                                'celtiis' => "Celtiis Cash",
                                'moov' => "Moov Money",
                              ])
                              ->label('Methode de payement')
                              ->required(),

                        Select::make('payment_status')
                              ->options([
                                'pending' => "En cours...",
                                'paid' => "Payé",
                                'failed' => "Echec payement",
                              ])
                              ->label('Statut du payement')
                              ->required()
                              ->default('pending'),

                        ToggleButtons::make('status')
                                     ->label("Statut")
                                     ->required()
                                     ->default('new')
                                     ->inline()
                                     ->options([
                                        'new' => "Nouvelle demande",
                                        'processing' => "En cours de traitement",
                                        'shipped' => "Expédié",
                                        'delivered' => "Livrée",
                                        'canceled' => "Annulée",
                                     ])
                                     ->colors([
                                        'new' => "info",
                                        'processing' => "warning",
                                        'shipped' => "success",
                                        'delivered' => "success",
                                        'canceled' => "danger",
                                     ])
                                     ->icons([
                                        'new' => "heroicon-m-sparkles",
                                        'processing' => "heroicon-m-arrow-path",
                                        'shipped' => "heroicon-m-truck",
                                        'delivered' => "heroicon-m-check-badge",
                                        'canceled' => "heroicon-m-x-circle",
                                     ]),
                        Select::make('currency')
                              ->options([
                                'inr' => "INR",
                                'usd' => "USD",
                                'cfa' => "CFA",
                              ])
                              ->label('Dévise')
                              ->required()
                              ->default('cfa'),

                        Select::make('shipping_method')
                              ->options([
                                'fedex' => "FedEx",
                                'ups' => "UPS",
                                'dhl' => "DHL",
                                'usps' => "USPS",
                                'gozem' => "GoZem",
                                'other' => "Autre",
                              ])
                              ->label('Methode de livraison')
                              ->required()
                              ->default('gozem'),

                        Textarea::make('notes')
                                ->columnSpanFull()
                                ->label("Notes (Faculative)")
                                ->placeholder("Des details complémentaires sur la demande...")
                    ])->columns(2),

                    Section::make('Listes des demandes')->schema([
                        Repeater::make('items')
                        ->label('Listes des articles')
                        ->relationship()
                        ->schema([
                            Select::make('product_id')
                            ->label("L'article")
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->distinct()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, Set $set) => $set('unit_amount', Product::find($state)?->price ?? 0))
                            ->afterStateUpdated(fn ($state, Set $set) => $set('total_amount', Product::find($state)?->price ?? 0))
                            ->columnSpan(4),
                        
                            TextInput::make('quantity')
                                   ->label("La quantité")
                                   ->numeric()
                                   ->required()
                                   ->default(1)
                                   ->minValue(1)
                                   ->reactive()
                                   ->afterStateUpdated(fn ($state, Set $set, Get $get) => $set('total_amount', $state * $get('unit_amount')))

                                   ->columnSpan(2),
                        
                            TextInput::make('unit_amount')
                                    ->label("Prix unitaire")
                                    ->numeric()
                                    ->required()
                                    ->disabled()
                                    ->dehydrated()
                                    ->suffix('CFA')
                                    ->columnSpan(3),
                            
                            TextInput::make('total_amount')
                                    ->label("Montant total")
                                    ->numeric()
                                   ->required()
                                   ->disabled()
                                   ->dehydrated()
                                   ->suffix('CFA')
                                   ->columnSpan(3),
                        ])->columns(12),

                        Placeholder::make('grand_total_placeholder')
                                    ->label('Facturatoin totale')
                                    ->content(function(Get $get, Set $set){
                                        $total = 0;

                                        if(!$repeaters = $get('items')){
                                            return $total;
                                        }

                                        foreach ($repeaters as $key => $repeater){
                                            $total += $get("items.{$key}.total_amount");
                                        }

                                        $set('grand_total', $total);

                                        return Number::currency($total, 'CFA');

                                    }),
                        Hidden::make('grand_total')->default(0),
                        
                    ]),

                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                           ->label('Client')
                           ->sortable()
                           ->searchable(),

                TextColumn::make('grand_total')
                           ->numeric()
                           ->label('Coût Total')
                           ->sortable()
                           ->money('CFA'),

                SelectColumn::make('payment_method')
                           ->searchable()
                           ->disabled()
                           ->options([
                            'stripe' => "Stripe",
                            'cod' => "Payement en liquide",
                            'mtn' => "Mobile Money MTN",
                            'celtiis' => "Celtiis Cash",
                            'moov' => "Moov Money",
                          ])
                           ->label('Methode de payement')
                           ->sortable(),

                SelectColumn::make('payment_status')
                           ->searchable()
                           ->label('Statut du payement')
                           ->sortable()
                           ->disabled()
                           ->options([
                            'pending' => "En cours...",
                            'paid' => "Payé",
                            'failed' => "Echec payement",
                           ]),

                SelectColumn::make('status')
                           ->label('Statut de la demande')
                           ->disabled()
                           ->options([
                            'new' => "Nouvelle demande",
                            'processing' => "En cours de traitement",
                            'shipped' => "Expédié",
                            'delivered' => "Livrée",
                            'canceled' => "Annulée",
                           ]),

                SelectColumn::make('currency')
                           ->label('Devise')
                           ->disabled()
                           ->options([
                            'inr' => "INR",
                            'usd' => "USD",
                            'cfa' => "CFA",
                           ]),
                
                SelectColumn::make('shipping_method')
                              ->options([
                                'fedex' => "FedEx",
                                'ups' => "UPS",
                                'dhl' => "DHL",
                                'usps' => "USPS",
                                'gozem' => "GoZem",
                                'other' => "Autre",
                              ])
                              ->disabled()
                              ->label('Methode de livraison'),
                
                TextColumn::make('created_at')
                    ->dateTime()
                    ->label("Date de Création")
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->label("Date de MAJ")
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                         

            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),

                ])
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


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() >= 10 ? static::getModel()::count() : '0' . static::getModel()::count();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
