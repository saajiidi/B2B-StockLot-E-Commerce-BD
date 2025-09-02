<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Dashboard Routes
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/seller/dashboard', 'HomeController@sellerDashboard')->name('seller.dashboard');
Route::get('/buyer/dashboard', 'HomeController@buyerDashboard')->name('buyer.dashboard');

// Product Routes
Route::resource('products', 'ProductController');
Route::get('/products/{product}/bulk-pricing', 'ProductController@getBulkPricing')->name('products.bulk-pricing');

// Order Routes
Route::resource('orders', 'OrderController')->except(['edit', 'update', 'destroy']);
Route::patch('/orders/{order}/status', 'OrderController@updateStatus')->name('orders.update-status');
Route::patch('/orders/{order}/cancel', 'OrderController@cancel')->name('orders.cancel');

// Quotation Routes
Route::resource('quotations', 'QuotationController')->except(['edit', 'update', 'destroy']);
Route::post('/orders/{order}/quotations', 'QuotationController@store')->name('orders.quotations.store');
Route::patch('/quotations/{quotation}/accept', 'QuotationController@accept')->name('quotations.accept');
Route::patch('/quotations/{quotation}/reject', 'QuotationController@reject')->name('quotations.reject');

// Category Routes
Route::get('/categories', 'CategoryController@index')->name('categories.index');
Route::get('/categories/{category}', 'CategoryController@show')->name('categories.show');

// Profile Routes
Route::get('/profile/{user}', 'ProfilesController@index')->name('profile.show');
Route::get('/profile/{user}/edit', 'ProfilesController@edit')->name('profile.edit');
Route::patch('/profile/{user}', 'ProfilesController@update')->name('profile.update');

// Admin Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/users', 'HomeController@myUsers')->name('admin.users');
});

// API Routes for AJAX calls
Route::prefix('api')->middleware('auth')->group(function () {
    Route::get('/products/{product}/bulk-pricing', 'ProductController@getBulkPricing');
});

// Stocklot specific routes
Route::get('/stocklots', 'ProductController@index')->defaults('stocklot', true)->name('stocklots.index');

// Search routes
Route::get('/search', 'SearchController@index')->name('search.index');
