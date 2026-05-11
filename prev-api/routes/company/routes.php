<?php


use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum', 'throttle:api', 'token_expiration'])->controller(CompanyController::class)->group(function () {
Route::get('/companies', 'GetCompanies');
Route::post('/companies', 'StoreCompany');
Route::get('/companies/{uuid}', 'EditCompany');
Route::put('/companies/{uuid}', 'UpdateCompany');
Route::delete('/companies/{uuid}', 'DeleteCompany');

});