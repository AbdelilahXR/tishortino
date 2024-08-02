<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            // stats 1 
            // here i can defind all stats of the order 
            Stat::make('New Orders', /* fetch total orders from the database
            ---> */Order::query()->where('status', 'new')->count()),

            //stats 2 
            // order processing count
            Stat::make('Order Processing' , Order::query()->where('status' , 'processing')->count()),

            //Order shipped
            Stat::make('Order Shipped' , Order::query()->where('status' , 'shipped')->count()),

            //stats of average price
            Stat::make('Average Price', Number::currency(Order::query()->avg('grand_total')  ?? 0 ,  'eur') ),


        ];
    }
}

