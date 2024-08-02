<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
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

    // to make it visible ( widgets / orderStats)
    protected function getHeaderWidgets(): array {
        return [
            OrderStats::class
        ];
    }
    //tab group
    public function getTabs(): array{
        return [
            null => Tab::make('All'),

            // fetching from orders in database with new status
            'new' => Tab::make()->query(fn ($query) => $query->where('status', 'new')),

            'processing' => Tab::make()->query(fn ($query) => $query->where('status', 'processing')),

            'shipped' => Tab::make()->query(fn ($query) => $query->where('status', 'shipped')),

            'Delivered' => Tab::make()->query(fn ($query) => $query->where('status', 'delivered')),
            
            'Cancelled' => Tab::make()->query(fn ($query) => $query->where('status', 'cancelled')),

            
        ];
    }
}
