<?php 

use App\Http\Controllers\SubCategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:api', 'token_expiration'])->controller(SubCategoryController::class)->group(function () {
 Route::get('/subcategories', 'GetSubCategories');
 Route::post('/subcategories', 'StoreSubCategory');
 Route::get('/subcategories/{uuid}', 'EditSubCategory');
 Route::put('/subcategories/{uuid}', 'UpdateSubCategory');
 Route::delete('/subcategories/{uuid}', 'DeleteSubCategory');

});