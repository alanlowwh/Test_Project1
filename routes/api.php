<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\cartAPI;

use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get("displayCartProduct",[cartAPI::class,'list']);
Route::get("displayCartProduct/{id}",[cartAPI::class,'listById']);

//Get all variation send to client
Route::get('/getProducts', [cartAPI::class,'displayPhone'])->name('display.variations');;

//Get request from client for add to cart. Server will return cartProduct here
Route::post('/cart/add', [cartAPI::class, 'addToCart'])->name('cart.add');


Route::get('/getEditCartView', [cartAPI::class,'editCartView'])->name('display.edit.cart');;

Route::post('/displayEditCart', [cartAPI::class,'displayEditCart'])->name('edit.cart.qty');;


//Hong Yao API
Route::get('/getAllProduct',[ProductController::class,'getAllProducts']);


