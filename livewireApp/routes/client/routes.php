<?php

use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:api', 'token_expiration'])->controller(ClientController::class)->group(function () {

Route::get('/clients', 'GetClients');
Route::post('/clients', 'StoreClient');
Route::get('/clients/{uuid}', 'EditClient');
Route::put('/clients/{uuid}', 'UpdateClient');
Route::delete('/clients/{uuid}', 'DeleteClient');
Route::get('/clients/{id?}', 'CloneClient');
});