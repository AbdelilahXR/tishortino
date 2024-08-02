<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaticController;


// will resume the long code in this 
Route::get('/', [StaticController::class, 'index']);
Route::get('/about', [StaticController::class, 'about']);
Route::get('/contact', [StaticController::class, 'contact']);








// Route::get('/store/{category?}/{item?}', function ($category = null , $item = null) {
//     if (isset($category)) {
//         if (isset($item)) {
//              //return '<h1>'.$item.'</h1>'; 
//              return '<h1>{$item}</h1>';  
//         };
//         return '<h1>'.$category.'</h1>' ;  
//     };

//     return '<h1>store</h1>'  ; 

// });






// Route::get('/sotre', function() {

//     $filter = request('style');
//     if (isset($filter)) {
//         return 'this page viewing <span style="color: red">'.strip_tags($filter).'</span>'; // strip againt xss attacks 
//     }
//    else {
//     return 'this page viewing <span style="color: red"> All Product'.'</span>';
//    }
// });