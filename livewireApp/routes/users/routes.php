<?php

use App\Http\Controllers\UserController;
use App\Livewire\Dashboard\Forms\{FormRenamePasswordComponent,FormUserComponent};
use \Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard\UserComponent;

Route::prefix('/dashboard')->middleware('is_authenticated')->group(function () {
    Route::get('/users', UserComponent::class)->name('app.dashboard.users');
    Route::get('/user/form/{uuid?}', FormUserComponent::class)->name('app.dashboard.form.user');
    Route::get('/user/form/clone/{id?}', FormUserComponent::class)->name('app.dashboard.clone.user');
    Route::get('/user/rename/password/{uuid}', FormRenamePasswordComponent::class)->name('app.dashboard.rename.password');
    Route::controller(UserController::class)->group(function () {
        Route::get('/users/report', 'GetUserReport')->name('app.dashboard.report.user');
        Route::get('/users/report/filter', 'GetUserReportDataByFilter')->name('app.dashboard.report.user.get.data');
    });

});
