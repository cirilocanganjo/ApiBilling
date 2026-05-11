<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentTypeController;

Route::middleware(['auth:sanctum', 'throttle:api', 'token_expiration'])->controller(PaymentTypeController::class)->group(function () {
    Route::get('/payment/types', 'GetPaymentTypes');
    Route::post('/payment/types', 'StorePaymentType');
    Route::get('/payment/types/{id}', 'EditPaymentType'); 
    Route::delete('/payment/types/{uuid}', 'DeletePaymentType'); 
    Route::put('/payment/types/{uuid}', 'UpdatePaymentType'); 
});
