<?php 

use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum', 'throttle:api', 'token_expiration'])->group(function () {
Route::get('/provinces', [\App\Http\Controllers\ProvinceController::class, 'GetProvinces']);
Route::post('/provinces', [\App\Http\Controllers\ProvinceController::class, 'StoreProvince']);
Route::get('/provinces/{id}', [\App\Http\Controllers\ProvinceController::class, 'EditProvince']);
Route::put('/provinces/{id}', [\App\Http\Controllers\ProvinceController::class, 'UpdateProvince']);
Route::delete('/provinces/{id}', [\App\Http\Controllers\ProvinceController::class, 'DeleteProvince']);

});