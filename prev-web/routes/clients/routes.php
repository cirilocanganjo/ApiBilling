<?php

use App\Livewire\Dashboard\ClientComponent;
use \Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard\Forms\{FormClientComponent};

Route::prefix('/dashboard')->middleware('is_authenticated')->group(function () {
Route::get('/clients', ClientComponent::class)->name('app.dashboard.clients');
Route::get('/clients/form/{uuid?}', FormClientComponent::class)->name('app.dashboard.form.client');
Route::get('/clients/clone/{id?}', FormClientComponent::class)->name('app.dashboard.clone.client');

});
