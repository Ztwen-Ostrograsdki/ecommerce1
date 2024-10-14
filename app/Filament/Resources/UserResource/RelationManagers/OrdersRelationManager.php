<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\OrderResource;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    protected static ?string $title = 'Derniers commandes effectuées';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                TextColumn::make('id')
                ->label("ID Achat")
                ->searchable(),

                TextColumn::make('grand_total')
                ->numeric()
                ->label('Coût Total')
                ->sortable()
                ->money('CFA'),

                SelectColumn::make('status')
                ->label('Statut de la demande')
                ->options([
                'new' => "Nouvelle demande",
                'processing' => "En cours de traitement",
                'shipped' => "Expédié",
                'delivered' => "Livrée",
                'canceled' => "Annulée",
                ])
                ->searchable()
                ->disabled()
                ->sortable(),

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
                ->sortable()
                ->searchable()
                ->label('Methode de livraison'),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->label("Date de MAJ")
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Action::make("Afficher")
                ->url(fn (Order $record): string => OrderResource::getUrl('view', ['record' => $record]))
                ->color('info')
                ->icon('heroicon-o-eye'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
