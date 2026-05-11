<?php 

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:api', 'token_expiration'])->controller(CategoryController::class)->group(function () {
    Route::get('/categories', 'GetCategories');
    Route::post('/categories', 'StoreCategory');
    Route::get('/categories/{uuid}', 'EditCategory');
    Route::put('/categories/{uuid}', 'UpdateCategory');
    Route::delete('/categories/{uuid}', 'DeleteCategory');
    
    });