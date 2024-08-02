<?php

// namespace App\Rules;

// use Closure;
// use Illuminate\Contracts\Validation\ValidationRule;

// // class StockCheck implements ValidationRule
// // {
// //     /**
// //      * Run the validation rule.
// //      *
// //      * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
// //      */
// //     public function validate(string $attribute, mixed $value, Closure $fail): void
// //     {
// //         //
// //     }
// // }

// class StockCheck implements Rule
// {
//     protected $productId;

//     public function __construct($productId)
//     {
//         $this->productId = $productId;
//     }

//     public function passes($attribute, $value)
//     {
//         $product = Product::find($this->productId);
//         return $product && $value <= $product->stock;
//     }

//     public function message()
//     {
//         $product = Product::find($this->productId);
//         return $product ? "The requested quantity for {$product->name} exceeds available stock." : "Product not found.";
//     }
// }