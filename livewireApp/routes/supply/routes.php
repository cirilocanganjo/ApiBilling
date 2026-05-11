<?php 

use \App\Http\Controllers\SupplyController;
use App\Models\Supply;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum', 'throttle:api', 'token_expiration'])->group(function () {
Route::get('/suppliers', [SupplyController::class, 'GetSuppliers']);
Route::post('/suppliers', [SupplyController::class, 'StoreSupplier']);
Route::get('/suppliers/{uuid}', [SupplyController::class, 'EditSupplier']);
Route::put('/suppliers/{uuid}', [SupplyController::class, 'UpdateSupplier']);
Route::delete('/suppliers/{uuid}', [SupplyController::class, 'DeleteSupplier']);
Route::get('/suppliers/{id?}', [SupplyController::class, 'CloneSupplier']);
});
