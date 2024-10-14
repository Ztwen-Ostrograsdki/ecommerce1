<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Illuminate\Support\Number;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Nouvelle Commande', Order::query()->where('status', 'new')->count()),

            Stat::make('Commande en cours', Order::query()->where('status', 'processing')->count()),

            Stat::make('Commandes en annulées', Order::query()->where('status', 'canceled')->count()),

            Stat::make('Commande livrée', Order::query()->where('status', 'shipped')->orWhere('status', 'delivered')->count()),

            Stat::make('Moyenne des prix', Number::currency(Order::query()->avg('grand_total'), 'CFA')),
        ];
    }



    
}
