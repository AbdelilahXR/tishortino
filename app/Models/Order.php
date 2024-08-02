<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;



class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'grand_total',
        'payment_method',
        'payment_status',
        'status',
        'currency',
        'shipping_amount',
        'shipping_method',
        'notes',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function items(){
        return $this->hasMany(OrderItem::class);
    }

    public function address(){
        return $this->hasOne(Address::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if (!$product || $product->stock < $item->quantity) {
                    throw new \Exception('Not enough stock available for product ID: ' . $item->product_id);
                }
            }
        });

        static::created(function ($order) {
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                $product->stock -= $item->quantity;
                $product->save();
            }
        });
    }
}





















// class Order extends Model
// {
//     use HasFactory;

//     protected $fillable = [
//         'user_id',
//         'grand_total',
//         'payment_method',
//         'payment_status',
//         'status',
//         'currency',
//         'shipping_amount',
//         'shipping_method',
//         'notes',
//     ];


//     public function user(){
//         return $this->belongsTo(User::class);
//     }

//     public function items(){
//         return $this->hasMany(OrderItem::class);
//     }

//     public function address(){
//         return $this->hasOne(Address::class);
//     }
// }
