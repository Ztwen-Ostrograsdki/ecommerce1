<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Actions;
use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\OrderResource\Widgets\OrderStats;
use Filament\Resources\Components\Tab;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            OrderStats::class,
        ];
    }

    public function getTabs(): array
    {
        return[
            null => Tab::make('Tout Lister'),
            'new' => Tab::make('Nouvelles commandes')->query(fn($query) => $query->where('status', 'new')),
            'processing' => Tab::make('En cours')->query(fn($query) => $query->where('status', 'processing')),
            'shipped' => Tab::make('Livrées')->query(fn($query) => $query->where('status', 'shipped')->orWhere('status', 'delivered')),
            'canceled' => Tab::make('Annulées')->query(fn($query) => $query->where('status', 'canceled')),

        ];
    }
}
