<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;

use App\Http\Controllers\Api\StripeController;
use App\Http\Controllers\LoginController;

// Route::post('user/login',[LoginController::class, 'authenticate']); 
Route::post('user/login', [UserController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('category/all', [CategoryController::class, 'all']);
Route::get('category/slug/{category:slug}', [CategoryController::class, 'slug']);

Route::get('post/all', [PostController::class, 'all']);
Route::get('post/slug/{slug}', [PostController::class, 'slug']);


Route::resource('post', PostController::class)->except(['create', 'edit']);
// Route::resource('category', CategoryController::class)->except(['create', 'edit']);
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::resource('category', CategoryController::class)->except(['create', 'edit']);
    // Route::resource('post', PostController::class)->except(['create', 'edit']); 
});

Route::post('post/upload/{post}', [PostController::class, 'upload']);

Route::post('user/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
Route::post('user/token-check', [UserController::class, 'checkToken']);


// stripe
Route::get('stripe/create-session/{priceId}/{successURL?}/{cancelUrl?}', [StripeController::class, 'createSession']);
Route::get('stripe/get-session/{sessionId}', [StripeController::class, 'checkPayment']);
Route::get('stripe/get-payment-intent/{paymentIntentId}', [StripeController::class, 'checkPaymentIntentByid']);
Route::get('stripe/customer', [StripeController::class, 'stripeCustomer']);
Route::get('stripe/balance', [StripeController::class, 'stripeBalance']);

