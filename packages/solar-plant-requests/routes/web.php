<?php

use Illuminate\Support\Facades\Route;
use SolarPlantRequests\Http\Controllers\AllSolarPlantRequestController;
use SolarPlantRequests\Http\Controllers\ContractorSelectionController;
use SolarPlantRequests\Http\Controllers\ContractorSolarPlantRequestController;
use SolarPlantRequests\Http\Controllers\InspectionSolarPlantRequestController;
use SolarPlantRequests\Http\Controllers\PackageSelectionController;
use SolarPlantRequests\Http\Controllers\Panel\CreateController;
use SolarPlantRequests\Http\Controllers\Inverter\CreateController as InverterCreateController;
use SolarPlantRequests\Http\Controllers\Battery\CreateController as BatteryCreateController;
use SolarPlantRequests\Http\Controllers\SolarPlantRequestController;
use SolarPlantRequests\Http\Controllers\UserApprovalController;

Route::middleware(['web', 'auth'])->prefix('solar-plant-requests')->name('solar-plant-requests.')->group(function () {

    // ─── تقاضا ───────────────────────────────────────────────────
    Route::get('/apply',                                [SolarPlantRequestController::class, 'apply'])->name('apply');
    Route::get('/',                                     [SolarPlantRequestController::class, 'index'])->name('index');
    Route::post('/',                                    [SolarPlantRequestController::class, 'store'])->name('store');
    Route::get('{solarPlantRequest}/detail',            [SolarPlantRequestController::class, 'detail'])->name('detail');
    Route::get('{solarPlantRequest}/show',              [SolarPlantRequestController::class, 'show'])->name('show');
    Route::get('file/download',                         [SolarPlantRequestController::class, 'downloadFile'])->name('file.download');

    // ─── تایید/رد گزارش کارشناسی توسط متقاضی ────────────────────
    Route::get('{solarPlantRequest}/expert-report',         [UserApprovalController::class, 'show'])->name('expert-report.show');
    Route::post('{solarPlantRequest}/expert-report/approve',[UserApprovalController::class, 'approve'])->name('expert-report.approve');
    Route::post('{solarPlantRequest}/expert-report/reject', [UserApprovalController::class, 'reject'])->name('expert-report.reject');

    // ─── انتخاب پکیج ─────────────────────────────────────────────
    Route::get('{solarPlantRequest}/package-selection',     [PackageSelectionController::class, 'show'])->name('package-selection');
    Route::post('{solarPlantRequest}/package-selection',    [PackageSelectionController::class, 'store'])->name('package-selection.store');

    // ─── انتخاب پیمانکار ─────────────────────────────────────────
    Route::get('{solarPlantRequest}/contractor-selection',  [ContractorSelectionController::class, 'show'])->name('contractor-selection');
    Route::post('{solarPlantRequest}/contractor-selection', [ContractorSelectionController::class, 'store'])->name('contractor-selection.store');

    // ─── همه درخواست‌ها (راهبر) ──────────────────────────────────
    Route::prefix('all-requests')->name('all-requests.')->group(function () {
        Route::get('/',                                          [AllSolarPlantRequestController::class, 'index'])->name('index');
        Route::post('{solarPlantRequest}/assign-contractor',     [AllSolarPlantRequestController::class, 'assignContractor'])->name('assign-contractor');
    });

    // ─── پیمانکار ────────────────────────────────────────────────
    Route::prefix('contractor')->name('contractor.')->group(function () {
        Route::get('/',                                          [ContractorSolarPlantRequestController::class, 'index'])->name('index');
        Route::get('{solarPlantRequest}/show',                   [ContractorSolarPlantRequestController::class, 'show'])->name('show');
        Route::post('{solarPlantRequest}/send-to-inspection',    [ContractorSolarPlantRequestController::class, 'sendToInspection'])->name('send-to-inspection');
    });

    // ─── پنل ─────────────────────────────────────────────────────
    Route::prefix('panel')->name('panel.')->group(function () {
        Route::get('my-panels',                                  [CreateController::class, 'myPanels'])->name('my-panels');
        Route::get('create',                                     [CreateController::class, 'create'])->name('create');
        Route::post('store',                                     [CreateController::class, 'store'])->name('store');
        Route::post('{solarPlantRequest}/store',                 [CreateController::class, 'addPanelToRequest'])->name('addPanelToRequest');
    });

    // ─── باتری ───────────────────────────────────────────────────
    Route::prefix('battery')->name('battery.')->group(function () {
        Route::get('my-batteries',                               [BatteryCreateController::class, 'myBatteries'])->name('my-batteries');
        Route::get('create',                                     [BatteryCreateController::class, 'create'])->name('create');
        Route::post('store',                                     [BatteryCreateController::class, 'store'])->name('store');
        Route::post('{solarPlantRequest}/store',                 [BatteryCreateController::class, 'addBatteryToRequest'])->name('addBatteryToRequest');
    });

    // ─── اینورتر ─────────────────────────────────────────────────
    Route::prefix('inverter')->name('inverter.')->group(function () {
        Route::get('my-inverters',                               [InverterCreateController::class, 'myInverters'])->name('my-inverters');
        Route::get('create',                                     [InverterCreateController::class, 'create'])->name('create');
        Route::post('store',                                     [InverterCreateController::class, 'store'])->name('store');
        Route::post('{solarPlantRequest}/store',                 [InverterCreateController::class, 'addInverterToRequest'])->name('addInverterToRequest');
    });

    // ─── بازرسی ──────────────────────────────────────────────────
    Route::prefix('inspection')->name('inspection.')->group(function () {
        Route::get('/',                                          [InspectionSolarPlantRequestController::class, 'index'])->name('index');
        Route::get('{solarPlantRequest}/show',                   [InspectionSolarPlantRequestController::class, 'show'])->name('show');
        Route::post('{solarPlantRequest}/result-approved',       [InspectionSolarPlantRequestController::class, 'approvedResult'])->name('result-approved');
        Route::post('{solarPlantRequest}/result-declined',       [InspectionSolarPlantRequestController::class, 'declinedResult'])->name('result-declined');
    });

});
