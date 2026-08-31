<?php

use Illuminate\Support\Facades\Route;
use RequestExpertReview\Http\Controllers\AdminAssignExpertController;
use RequestExpertReview\Http\Controllers\ExpertRequestController;

Route::middleware(['web', 'auth'])->group(function () {

    // --- راهبر: اساین کارشناس به تقاضا ---
    Route::prefix('admin/request-expert-review')->name('request-expert-review.admin.')->group(function () {
        Route::get('/',                                              [AdminAssignExpertController::class, 'index'])->name('index');
        Route::post('{solarPlantRequest}/assign-expert',             [AdminAssignExpertController::class, 'assignExpert'])->name('assign-expert');
    });

    // --- کارشناس: لیست و مدیریت تقاضاها ---
    Route::prefix('expert/requests')->name('request-expert-review.expert.')->group(function () {
        Route::get('/',                           [ExpertRequestController::class, 'index'])->name('index');
        Route::get('{solarPlantRequest}',         [ExpertRequestController::class, 'show'])->name('show');
        Route::put('{solarPlantRequest}/update',  [ExpertRequestController::class, 'update'])->name('update');
    });

});
