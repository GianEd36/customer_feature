<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CouponController;

use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/products/{id}', [ProductController::class, 'show']);
Route::post('/cart/add', [CartController::class, 'add']);
Route::get('/cart', [CartController::class, 'index']);
Route::post('/checkout', [OrderController::class, 'checkout']);
Route::post('/apply-coupon', [CartController::class, 'applyCoupon']);
Route::get('/order/{id}', [OrderController::class, 'show']);
Route::get('/orders/history', [OrderController::class, 'history']);
Route::post('/products/{id}/review', [ReviewController::class, 'store']);
Route::post('/coupon/apply', [CouponController::class, 'apply']);
