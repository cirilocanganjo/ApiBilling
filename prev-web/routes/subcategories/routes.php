<?php

use App\Livewire\Dashboard\Forms\{FormSubCategoryComponent};
use App\Livewire\Dashboard\SubCategoryComponent;
use \Illuminate\Support\Facades\Route;

Route::prefix('/dashboard')->middleware('is_authenticated')->group(function () {
Route::get('/subcategories', SubCategoryComponent::class)->name('app.dashboard.subcategories');
Route::get('/subcategory/form', FormSubCategoryComponent::class)->name('app.dashboard.form.subcategory');
Route::get('/subcategory/form/{uuid}', FormSubCategoryComponent::class)->name('app.dashboard.edit.subcategory');
});
