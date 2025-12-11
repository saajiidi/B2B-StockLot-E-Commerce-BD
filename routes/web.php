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

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes...
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Registration Routes...
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Password Reset Routes...
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Email Verification Routes...
Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');
Route::post('email/resend', [VerificationController::class, 'resend'])
    ->middleware('throttle:6,1')
    ->name('verification.resend');

// Dashboard Routes
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/seller/dashboard', [HomeController::class, 'sellerDashboard'])->name('seller.dashboard');
Route::get('/buyer/dashboard', [HomeController::class, 'buyerDashboard'])->name('buyer.dashboard');

// Product Routes
Route::resource('products', ProductController::class);
Route::get('/products/{product}/bulk-pricing', [ProductController::class, 'getBulkPricing'])->name('products.bulk-pricing');

// Order Routes
Route::resource('orders', OrderController::class)->except(['edit', 'update', 'destroy']);
Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

// Quotation Routes
Route::resource('quotations', QuotationController::class)->except(['edit', 'update', 'destroy']);
Route::post('/orders/{order}/quotations', [QuotationController::class, 'store'])->name('orders.quotations.store');
Route::patch('/quotations/{quotation}/accept', [QuotationController::class, 'accept'])->name('quotations.accept');
Route::patch('/quotations/{quotation}/reject', [QuotationController::class, 'reject'])->name('quotations.reject');

// Category Routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// Profile Routes
Route::get('/profile/{user}', [ProfilesController::class, 'index'])->name('profile.show');
Route::get('/profile/{user}/edit', [ProfilesController::class, 'edit'])->name('profile.edit');
Route::patch('/profile/{user}', [ProfilesController::class, 'update'])->name('profile.update');

// Admin Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/users', [HomeController::class, 'myUsers'])->name('admin.users');
});

// API Routes for AJAX calls
Route::prefix('api')->middleware('auth')->group(function () {
    Route::get('/products/{product}/bulk-pricing', [ProductController::class, 'getBulkPricing']);
});

// Stocklot specific routes
Route::get('/stocklots', [ProductController::class, 'index'])->defaults('stocklot', true)->name('stocklots.index');

// Search routes
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
