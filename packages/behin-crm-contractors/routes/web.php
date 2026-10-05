<?php

use BehinCrmContractors\Http\Controllers\CrmContractorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->prefix('crm-contractors')->name('crm-contractors.')->group(function () {
    Route::get('/',      [CrmContractorController::class, 'index'])->name('index');
    Route::post('/sync', [CrmContractorController::class, 'triggerSync'])->name('sync');
});
