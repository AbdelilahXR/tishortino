<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description','brand_id','category_id',  'images', 'price', 'size', 'color','stock', 'in_stock','is_featured', 'on_sale'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


//reduce stock 
    public function reduceStock($quantity)
    {
        if ($this->stock >= $quantity) {
            $this->stock -= $quantity;
            $this->save();
        } else {
            throw new \Exception('Not enough stock available.');
        }
    }





    protected $casts = [
        'images' => 'array', // Cast images as an array when retrieving
    ];
    

   
}
