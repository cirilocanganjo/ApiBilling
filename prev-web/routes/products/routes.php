<?php

use App\Livewire\Dashboard\ProductComponent;
use \Illuminate\Support\Facades\Route;

Route::prefix('/dashboard')->middleware('is_authenticated')->group(function () {
    Route::get('/products', ProductComponent::class)->name('app.dashboard.products');
});
