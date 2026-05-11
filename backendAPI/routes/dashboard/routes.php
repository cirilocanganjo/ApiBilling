<?php

use \Illuminate\Support\Facades\Route;
use \App\Livewire\Dashboard\{
    BrandComponent,
    CategoryComponent,
    UserComponent,
    SubCategoryComponent,
    UnitComponent,
    CityComponent,
    CompanyComponent,
    DashboardComponent,
    FormCategory,
    SupplyComponent,
    FormBrand,
    FormClient,
    FormCompany,
    FormCity,
    FormSupplyComponent,
    FormUnit,
    FormSubCategoryComponent,
    FormUserComponent
};


Route::prefix('/dashboard')->middleware('is_authenticated')->group(function () {
Route::get('/home', DashboardComponent::class)->name('app.dashboard');
Route::get('/brands', BrandComponent::class)->name('app.dashboard.brands');
Route::get('/form/brand', FormBrand::class)->name('app.dashboard.form.brand');
Route::get('/form/brand/{uuid}', FormBrand::class)->name('app.dashboard.edit.brand');

Route::get('/companies', CompanyComponent::class)->name('app.dashboard.companies');
Route::get('/form/company', FormCompany::class)->name('app.dashboard.form.company');
Route::get('/form/company/{uuid}', FormCompany::class)->name('app.dashboard.edit.company');
Route::get('/cities', CityComponent::class)->name('app.dashboard.cities');
Route::get('/form/city', FormCity::class)->name('app.dashboard.form.city');
Route::get('/form/city/{uuid}', FormCity::class)->name('app.dashboard.edit.city');

});
