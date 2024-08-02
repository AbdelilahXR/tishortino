<?php

// app/Filament/Resources/ProductResource.php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationGroup = 'Shop';


    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Product Information')->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live('onBlur', true)
                            ->afterStateUpdated(function (string $operation, $state, $set) {
                                if ($operation !== 'create') {
                                    return;
                                }
                                $set('slug', Str::slug($state));
                            }),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated()
                            ->unique(Product::class, 'slug', ignoreRecord: true),

                        MarkdownEditor::make('description')
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory('products'),

                        // Parent Category Field
                        Select::make('parent_id')
                            ->label('Parent Category')
                            ->options(Category::whereNull('parent_id')->pluck('name', 'id')->toArray())
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set) {
                                // Reset category_id field when parent_id changes
                                $set('category_id', null);
                                // Set the categories available for the selected parent category
                                $set('categories', Category::where('parent_id', $state)->pluck('name', 'id')->toArray());
                            }),

                        // Category Field
                        Select::make('category_id')
                            ->label('Category')
                            ->options(fn($get) => Category::where('parent_id', $get('parent_id'))->pluck('name', 'id')->toArray())
                            ->searchable()
                            ->required(),
                    ])->columns(2),

                    Section::make('Images')->schema([
                        FileUpload::make('images')
                            ->multiple()
                            ->directory('Products')
                            ->maxFiles(5)
                            ->reorderable()
                    ])
                ])->columnSpan(2),

                Group::make()->schema([
                    Section::make('Price')->schema([
                        TextInput::make('price')
                            ->numeric()
                            ->required()
                            ->prefix('€'),
                    ]),
                    Section::make('Attributes')->schema([
                        Select::make('size')
                            ->options([
                                'S' => 'Small',
                                'M' => 'Medium',
                                'L' => 'Large',
                                'XL' => 'Extra Large',
                            ])
                            ->required()
                            ->placeholder('Select Size'),

                        Select::make('color')
                            ->options([
                                'Red' => 'Red',
                                'Green' => 'Green',
                                'Blue' => 'Blue',
                                'Yellow' => 'Yellow',
                                'Black' => 'Black',
                            ])
                            ->placeholder('Choose Color'),

                        TextInput::make('custom_color')
                            ->label('Custom Color')
                            ->maxLength(255)
                            ->placeholder('Or enter a custom color'),
                    ])->columns(2),

                    Section::make('Stock')->schema([
                        TextInput::make('stock')
                            ->numeric()
                            ->required()
                            ->default(0),
                    ])->columns(0),

                    Section::make('Status')->schema([
                        Toggle::make('in_active')
                            ->required()
                            ->default(true),
                        Toggle::make('is_featured')
                            ->default(false)
                    ]),
                ])->columnSpan(1)
            ])->columns(3);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('category.name')->label('Category')->sortable(),
                BooleanColumn::make('in_stock')->label('In Stock'),
                BooleanColumn::make('is_active')->label('Is Active'),
                BooleanColumn::make('is_featured')->label('Featured'),
                TextColumn::make('stock')
                    ->label('Stock')
                    ->formatStateUsing(function ($state) {
                        return $state == 0 ? '<span style="color:red;">Stock is empty</span>' : $state;
                    })->html()->sortable(),
                TextColumn::make('price')->prefix('€')->sortable(),
            ])->filters([
                SelectFilter::make('category_id')->label('Category')
                    ->options(Category::all()->pluck('name', 'id')->toArray()),

                SelectFilter::make('size')->label('Size')
                    ->options([
                        'S' => 'Small',
                        'M' => 'Medium',
                        'L' => 'Large',
                        'XL' => 'Extra Large',
                    ]),

                SelectFilter::make('color')->label('Color')
                    ->options([
                        'Red' => 'Red',
                        'Green' => 'Green',
                        'Blue' => 'Blue',
                        'Yellow' => 'Yellow',
                        'Black' => 'Black',

                    ])->searchable()
                


               

                
            ]) 
            
            ->actions([
               
                Tables\Actions\ActionGroup::make([
                       Tables\Actions\EditAction::make(),
                       Tables\Actions\ViewAction::make(),
                       Tables\Actions\DeleteAction::make(),
                ])
                ])->bulkActions([
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
