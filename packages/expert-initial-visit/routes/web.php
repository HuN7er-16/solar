<?php

use ExpertInitialVisit\Http\Controllers\ExpertInitialVisitController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->prefix('expert/initial-visit')->name('expert-initial-visit.')->group(function () {
    Route::get('/',               [ExpertInitialVisitController::class, 'index'])->name('index');
    Route::get('create',          [ExpertInitialVisitController::class, 'create'])->name('create');
    Route::post('/',              [ExpertInitialVisitController::class, 'store'])->name('store');
    Route::get('{expertInitialVisit}', [ExpertInitialVisitController::class, 'show'])->name('show');
});
