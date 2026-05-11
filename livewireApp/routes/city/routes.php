<?php 

use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum', 'throttle:api', 'token_expiration'])->group(function () {
Route::get('/cities', [\App\Http\Controllers\CityController::class, 'GetCities']);
Route::post('/cities', [\App\Http\Controllers\CityController::class, 'StoreCity']);
Route::get('/cities/{uuid}', [\App\Http\Controllers\CityController::class, 'EditCity']);
Route::put('/cities/{uuid}', [\App\Http\Controllers\CityController::class, 'UpdateCity']);
Route::delete('/cities/{uuid}', [\App\Http\Controllers\CityController::class, 'DeleteCity']);

});