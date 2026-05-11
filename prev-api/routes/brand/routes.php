<?php

use App\Http\Controllers\BrandController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:api', 'token_expiration'])->controller(BrandController::class)->group(function () {
 Route::get('/brands', 'GetBrands');
 Route::post('/brands', 'StoreBrand');
 Route::get('/brands/{uuid}', 'EditBrand');
 Route::put('/brands/{uuid}', 'UpdateBrand');
 Route::delete('/brands/{uuid}', 'DeleteBrand');

});