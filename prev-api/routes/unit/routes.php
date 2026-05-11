<?php

use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:api', 'token_expiration'])->controller(UnitController::class)->group(function () {

Route::get('/units', 'GetUnits');
Route::post('/units', 'StoreUnit');
Route::get('/units/{uuid}', 'EditUnit');
Route::put('/units/{uuid}', 'UpdateUnit');
Route::delete('/units/{uuid}', 'DeleteUnit');


});