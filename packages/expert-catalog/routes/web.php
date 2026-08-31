<?php

use ExpertCatalog\Http\Controllers\ExpertCatalogController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->prefix('admin/expert-catalog')->name('expert-catalog.')->group(function () {
    Route::get('/',                [ExpertCatalogController::class, 'index'])->name('index');
    Route::get('create',           [ExpertCatalogController::class, 'create'])->name('create');
    Route::post('store',           [ExpertCatalogController::class, 'store'])->name('store');
    Route::get('last-record',      [ExpertCatalogController::class, 'lastRecord'])->name('last-record');
    Route::get('{expert}',         [ExpertCatalogController::class, 'show'])->name('show');
    Route::get('{expert}/edit',    [ExpertCatalogController::class, 'edit'])->name('edit');
    Route::put('{expert}',         [ExpertCatalogController::class, 'update'])->name('update');
    Route::delete('{expert}',      [ExpertCatalogController::class, 'destroy'])->name('destroy');
});
