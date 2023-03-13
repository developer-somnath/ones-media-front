<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home;
use App\Http\Controllers\Authentication;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\OrderController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [Home::class, 'index'])->name('home');
Route::get('/shows-all', [Home::class, 'showsAll'])->name('shows.all');
Route::get('/search', [Home::class, 'searchAll'])->name('search');
Route::get('/new-shows', [Home::class, 'newShows'])->name('new.shows');
Route::get('/show-by-category/{slug?}', [Home::class, 'showByCategory'])->name('show.by.category');
Route::get('/show-by-year/{year?}', [Home::class, 'showByYear'])->name('show.by.Year');
Route::match(['get', 'post'], 'show/details/{id}', [Home::class, 'showDetails'])->name('show.details');
Route::get('/cart', [Home::class, 'cart'])->name('cart');
Route::post('/add-to-cart', [Home::class, 'addToCart'])->name('add.to.cart');
Route::match(['get', 'post'], '/update-cart-quantity', [Home::class, 'updateCartQuantity'])->name('update-cart-quantity');
Route::post('/remove-from-cart', [Home::class, 'removeFromCart'])->name('remove.from.cart');
/* Route::match(['get', 'post'], '/update-cart-quantity', [Dashboard::class, 'index'])->name('update-my-cart-quantity'); */
Route::post('/filter-by-cart', [Home::class, 'filterByCart'])->name('filter.by.cart');
Route::get('/about-us', [Home::class, 'aboutUs'])->name('about-us');
Route::get('/terms-conditions', [Home::class, 'termsConditions'])->name('terms-conditions');
Route::get('/privacy-policy', [Home::class, 'privacyPolicy'])->name('privacy-policy');

Route::get('login', [Authentication::class, 'login'])->name('login');
Route::match(['get', 'post'],'register', [Authentication::class, 'register'])->name('signup');
Route::match(['get', 'post'],'user-check', [Authentication::class, 'userCheck'])->name('user-check');
Route::post('state-list-by-country-id', [Authentication::class, 'stateListByCountryId'])->name('state-list-by-country-id');


Route::middleware(['auth'])->group(function () {
    /* Route::get('/', [Dashboard::class, 'index']); */
    Route::match(['get','post'],'/my-account', [Dashboard::class, 'index'])->name('my-account');
    Route::match(['get','post'],'/update-my-cart-quantity', [Dashboard::class, 'updateMyCartQuantity'])->name('update-my-cart-quantity');
    Route::match(['get', 'post'], '/change-password', [Dashboard::class, 'changePassword'])->name('change-password');
    Route::get('logout', [Authentication::class, 'logout'])->name('logout');
    Route::post('/add-to-wishlist', [Home::class, 'addToWishlist'])->name('add.to.wishlist');
    Route::post('/add-sampleFile-to-cart', [Home::class, 'addSampleFileToCart'])->name('add.sample.file.to.cart');
    Route::post('/add-to-cart-from-wishlist', [Home::class, 'addToCartFromWishlist'])->name('add.to.cart.wishlist');
    Route::post('/remove-from-wishlist', [Home::class, 'removeFromWishlist'])->name('remove.from.wishlist');
    Route::post('/filter-by-my-cart', [Home::class, 'filterByMyCart'])->name('filter.by.my.cart');
    Route::post('generic-status-change-delete', [Authenticate::class, 'genericStatusChange'])->name('generic-status-change-delete');
    Route::get('/my-cart', [Authentication::class, 'myCart'])->name('my-cart');
    Route::get('/my-wishlist', [Authentication::class, 'myWishlist'])->name('my-wishlist');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::get('/order-history', [OrderController::class, 'orderHistory'])->name('order-history');
    Route::get('/purchased-recordings', [OrderController::class, 'purchasedRecordings'])->name('purchased-recordings');
    Route::post('ajax-order-list', [OrderController::class, 'ajaxDataTable'])->name('ajax-order-list');
    Route::get('order-details/{id}', [OrderController::class, 'details'])->name('order-details');
    Route::get('download/{id}', [OrderController::class, 'createZipFile'])->name('download');
    Route::post('/place-order', [OrderController::class, 'placeOrder'])->name('place.order');
    Route::get('/payment', [OrderController::class, 'payment'])->name('payment');
    Route::post('/create-order-stripe', [OrderController::class, 'createOrderStripe'])->name('create-stripe-order');
    Route::get('/payment-record', [OrderController::class, 'orderCreate'])->name('create-stripe-order');
    Route::get('/order-summery/{id}', [OrderController::class, 'summery'])->name('order-summery');
    
    Route::get('/sample-file', [Home::class, 'sampleFile'])->name('sample-file');

});
