<?php
use \Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard\CategoryComponent;
use App\Livewire\Dashboard\Forms\{FormCategoryComponent};


Route::prefix('/dashboard')->middleware('is_authenticated')->group(function () {
    Route::get('/categories', CategoryComponent::class)->name('app.dashboard.categories');
    Route::get('/category/form/', FormCategoryComponent::class)->name('app.dashboard.form.category');
    Route::get('/category/form/{uuid}', FormCategoryComponent::class)->name('app.dashboard.edit.category');
});
