<?php
use \Illuminate\Support\Facades\Route;
use \App\Livewire\Dashboard\UnitComponent;
use \App\Livewire\Dashboard\Forms\FormUnitComponent;
Route::prefix('/dashboard')->middleware('is_authenticated')->group(function () {
    Route::get('/units', UnitComponent::class)->name('app.dashboard.units');
    Route::get('/unit/form/', FormUnitComponent::class)->name('app.dashboard.form.unit');
    Route::get('/unit/form/{uuid}', FormUnitComponent::class)->name('app.dashboard.edit.unit');
});
