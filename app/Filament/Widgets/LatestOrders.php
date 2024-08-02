<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Order;

class LatestOrders extends BaseWidget
{


    //full width for orderrelationmanager
    protected int | string | array $columnSpan = 'full';


    //to display order stats up to latest orders
    protected static ?int  $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(OrderResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')


            ->columns([
                //order -> i copied it from orderrelationmanager 
                //display order id and search bar
                TextColumn::make('id')
                    ->label('Order ID')
                    ->searchable(),

                 //display name of user
                 TextColumn::make('user.name')
                    ->searchable(),   
                
                // grand total
                TextColumn::make('grand_total')
                    ->money('EUR'),    
                // display order status
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state):string => match($state){ // call back method
                        'new' => 'info',
                        'processing' => 'warning',
                        'shipped' => 'success',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                    })
                    ->icon(fn (string $state):string => match($state){
                        'new' => 'heroicon-m-sparkles',
                        'processing' => 'heroicon-m-arrow-path',
                        'shipped' => 'heroicon-m-truck',
                        'delivered' => 'heroicon-m-check-badge',
                        'cancelled' => 'heroicon-m-x-circle',
                    })
                    ->sortable(),

                    //payment
                    TextColumn::make('payment_method')
                        ->sortable()
                        ->searchable(),    
                    
                    //payment status 
                    TextColumn::make('payment_status')
                        ->sortable()
                        ->badge()
                        ->searchable(),

                    //created & updated
                    TextColumn::make('created_at')
                        ->label('Order Date')
                        ->dateTime()


                ])


              
                ->actions([
                    Tables\Actions\Action::make('View Order')
                    ->url(fn (Order $record): string => OrderResource::getUrl('view', ['record' => $record]))
                    ->icon('heroicon-m-eye'),
                ]);
    }
}

