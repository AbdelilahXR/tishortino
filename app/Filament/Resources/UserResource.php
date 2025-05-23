<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\OrdersRelationManager;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Shop';


    //global search bar, filament docs
    protected static ?string $recordTitleAttribute = 'name';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('name')
                    ->required(),

                Forms\Components\TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->required(),

                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->label('Email verified at')
                    ->default(now()),

                    Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state)) 
                    ->required(fn ( $livewire): bool => $livewire instanceof CreateUser)
                

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
       


                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->searchable(),


                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()

                
                


            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),

                 // i want more actions view delete
                 
                // Tables\Actions\ViewAction::make(),
                // Tables\Actions\DeleteAction::make(),

                //combine it in a group
                Tables\Actions\ActionGroup::make([
                       Tables\Actions\EditAction::make(),
                       Tables\Actions\ViewAction::make(),
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
            // to make order relation visible 
            OrdersRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
