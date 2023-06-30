<?php


use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;

// use App\Models\Product;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });




//------------------Product------------------------Wai Horng only for display 
Route::get('/allProduct', [ProductController::class,'displayPhone'])->name('display.variations');;

//-------------------Cart---------------------------------
//view using hardcoded xml
// Route::get('/cart/view', 'App\Http\Controllers\CartController@showCartProducts')->name('viewCartProduct');

//View cart in xml. Get data from db convert to xml,xlst,dtd
Route::get('/export-cart-products', 'App\Http\Controllers\CartController@exportCartProducts')->name('export.cart.products');

//Edit card using laravel only
Route::get('/view-edit-cart', 'App\Http\Controllers\CartController@index')->name('view.cart');
Route::get('/edit-cart-qty', 'App\Http\Controllers\CartController@editCartQty')->name('edit.cart.qty');
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('cart.add');
Route::delete('/delete-cart-product/{variationId}', [CartController::class, 'deleteProduct'])->name('cart.product.delete');


//Hong yao------------------------------------------------------------------

Route::get('/products', 'App\Http\Controllers\ProductController@index')->name('products.index');
// Route::resource('product', ProductController::class);
Route::delete('/products/{id}', 'App\Http\Controllers\ProductController@destroy')->name('products.destroy');
Route::get('/products/{id}/edit', 'App\Http\Controllers\ProductController@edit')->name('products.edit');
Route::post('/products/{id}/edit', 'App\Http\Controllers\ProductController@update')->name('products.update');
Route::get('/products/create', 'App\Http\Controllers\ProductController@create')->name('products.create');
Route::post('/products', 'App\Http\Controllers\ProductController@store')->name('products.store');
// Route::get('/allProduct', [ProductController::class,'displayPhone']);
Route::get('/product', [ProductController::class, 'productIndex'])->name("product"); //xml




//Tang quan---------------------------------------------------------------------------------------------------------------User module
Route::get('/', [HomeController::class, 'index'])->name('home.home');

Route::group(['namespace' => 'App\Http\Controllers'], function () {

    Route::group(['middleware' => ['guest']], function () {
        
        
        /**
         * Register Routes
         */
        Route::get('/register', 'RegisterController@show')->name('register.show');
        Route::post('/register', 'RegisterController@register')->name('register.perform');

        /**
         * Login Routes
         */
        Route::get('/login', 'LoginController@show')->name('login.show');
        Route::post('/login', 'LoginController@login')->name('login.perform');
        Route::get('/session-data', function () {
            dd(session()->all());
        });
    });

    Route::group(['middleware' => ['auth']], function () {
        /**
         * Logout Routes
         */
        Route::get('/logout', 'LogoutController@perform')->name('logout.perform');

        /**
         * User Routes
         */
        Route::get('/user', [UserController::class, 'customerIndex'])->name('user')->middleware('staff');
        Route::get('/userprofile', [UserController::class, 'showUserProfile'])->name('users.userprofile');
        Route::resource('users', UserController::class);    



        /**farn meng */
        Route::get('checkout', [CheckoutController::class,'index'])->name('index.checkout');
        Route::post('confirm-order',[CheckoutController::class, 'confirmOrder']);
        Route::get('orders', [OrderController::class, 'index'])->middleware('staff')->name('orders.list');
        Route::get('view-order/{id}', [OrderController::class, 'view'])->middleware('staff');
        Route::put('update-order/{id}', [OrderController::class, 'update'])->middleware('staff');
        Route::get('order-history',[OrderController::class, 'orderHistory'])->middleware('staff');
        Route::get('/orders/report', 'OrderController@generateReport')->name('orders.report')->middleware('staff');

    });

    Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
    Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
    Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
    Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');
});





