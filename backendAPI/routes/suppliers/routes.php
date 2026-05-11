<?php

use \App\Livewire\Dashboard\Forms\{FormSupplyComponent};
use \App\Livewire\Dashboard\SupplyComponent;
use \Illuminate\Support\Facades\Route;

Route::prefix('/dashboard')->middleware('is_authenticated')->group(function () {
Route::get('/suppliers', SupplyComponent::class)->name('app.dashboard.suppliers');
Route::get('/supply/form/{uuid?}', FormSupplyComponent::class)->name('app.dashboard.form.supplier');
Route::get('/supply/clone/{id?}', FormSupplyComponent::class)->name('app.dashboard.clone.supplier');
});
