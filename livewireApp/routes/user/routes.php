<?php

use App\Http\Controllers\UserController;
use \Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum', 'throttle:api', 'token_expiration'])->controller(UserController::class)->group(function () {
Route::get('/users', 'GetUsers');
Route::post('/users', 'StoreUser');
Route::get('/users/{uuid}', 'EditUser');
Route::put('/users/{uuid}', 'UpdateUser');
Route::delete('/users/{uuid}', 'DeleteUser');
Route::get('/users/clone/{id}', 'CloneUser');
Route::put('/users/rename/password/{uuid}', 'RenamePassword');
Route::get('/users-reports', 'GetUserReport');
});