<?php

use Illuminate\Support\Facades\Route;
use ContractorCatalog\Http\Controllers\ContractorCatalogController;
use ContractorCatalog\Http\Controllers\ContractorRegistrationController;

// ثبت‌نام پیمانکار - بدون احراز هویت
Route::middleware(['web'])->group(function () {
    Route::get('contractor/register',  [ContractorRegistrationController::class, 'showForm'])->name('contractor.register');
    Route::post('contractor/register', [ContractorRegistrationController::class, 'register'])->name('contractor.register.submit');
});

Route::middleware(['web', 'auth'])->prefix('admin/contractor-catalog')->name('contractor-catalog.')->group(function () {
    Route::get('/',              [ContractorCatalogController::class, 'index'])->name('index');
    Route::get('create',         [ContractorCatalogController::class, 'create'])->name('create');
    Route::post('store',         [ContractorCatalogController::class, 'store'])->name('store');
    Route::get('last-record',    [ContractorCatalogController::class, 'lastRecord'])->name('last-record');
    Route::get('{contractor}',         [ContractorCatalogController::class, 'show'])->name('show');
    Route::get('{contractor}/edit',    [ContractorCatalogController::class, 'edit'])->name('edit');
    Route::put('{contractor}',         [ContractorCatalogController::class, 'update'])->name('update');
    Route::delete('{contractor}',      [ContractorCatalogController::class, 'destroy'])->name('destroy');
});
