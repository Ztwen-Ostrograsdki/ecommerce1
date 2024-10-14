<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use App\Models\Order;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\UserResource;
use Filament\Tables\Columns\ImageColumn;
use App\Filament\Resources\OrderResource;
use Filament\Tables\Columns\SelectColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Actions\DeleteAction as ActionsDeleteAction;

class LatestOrders extends BaseWidget
{

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Les derniers commandes enregistrées';

    protected static ?int $sort = 3;


   
    
    public function table(Table $table): Table
    {
        return $table
            ->query(OrderResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')

            ->columns([
                ImageColumn::make('user.profil_photo')
                ->circular(true)
                ->url(fn(Order $record): string => UserResource::getUrl('edit', ['record' => $record->user])),
                TextColumn::make('user.name')
                ->label('Client')
                ->sortable()
                ->searchable()
                ->url(fn(Order $record): string => UserResource::getUrl('edit', ['record' => $record->user])),


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
            ->actions([
                Action::make('Afficher')
                ->url(fn(Order $record): string => OrderResource::getUrl('view', ['record' => $record]))
                ->color('info')
                ->icon('heroicon-o-eye'),

                ActionsDeleteAction::make('Supprimer')
                ->color('danger')
                ->recordTitle('Supprimer cet article')
                ->icon('heroicon-o-x-circle')
            ]);
    }
}
