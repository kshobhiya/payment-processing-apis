<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaymentOrderController;
use App\Http\Controllers\PaymentTransactionController;

Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/payment-orders',[PaymentOrderController::class, 'store']);
    Route::post('/payment-orders/{paymentOrder}/pay',[PaymentTransactionController::class, 'processPayment']);
    Route::get('/payment-orders/{paymentOrder}',[PaymentOrderController::class, 'show']);
    Route::get('/payment-orders/{paymentOrder}/transactions',[PaymentTransactionController::class, 'history']);
});
