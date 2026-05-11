<?php 

use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum', 'throttle:api', 'token_expiration'])->group(function () {
Route::get('/countries', [\App\Http\Controllers\CountryController::class, 'GetCountries']);
Route::post('/countries', [\App\Http\Controllers\CountryController::class, 'StoreCountry']);
Route::get('/countries/{id}', [\App\Http\Controllers\CountryController::class, 'EditCountry']);
Route::put('/countries/{id}', [\App\Http\Controllers\CountryController::class, 'UpdateCountry']);
Route::delete('/countries/{id}', [\App\Http\Controllers\CountryController::class, 'DeleteCountry']);

});