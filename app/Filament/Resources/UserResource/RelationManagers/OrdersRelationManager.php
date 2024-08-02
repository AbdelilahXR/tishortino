<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope; 

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //m

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                //display order id and search bar
                TextColumn::make('id')
                    ->label('Order ID')
                    ->searchable(),

                // grand total
                TextColumn::make('grand_total')
                    ->money('mad'),    
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


            ->filters([
                //
            ])
            ->headerActions([
                 // hide create order
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                //detail of order view page 
                Tables\Actions\Action::make('View Order')
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
