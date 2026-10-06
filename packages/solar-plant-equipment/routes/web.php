<?php

use Illuminate\Support\Facades\Route;
use SolarPlantEquipment\Http\Controllers\ContractorProjectsController;
use SolarPlantEquipment\Http\Controllers\InstalledBatteryController;
use SolarPlantEquipment\Http\Controllers\InstalledInverterController;
use SolarPlantEquipment\Http\Controllers\InstalledPanelController;
use SolarPlantEquipment\Http\Controllers\SolarProjectController;

// ─── روت‌های ادمین/راهبر ──────────────────────────────────────────────────
Route::middleware(['web', 'auth'])->prefix('admin/solar-projects')->name('solar-plant-equipment.projects.')->group(function () {

    // ── Project CRUD ──────────────────────────────────────────────────────────
    Route::get('/',              [SolarProjectController::class, 'index'])->name('index');
    Route::get('create',         [SolarProjectController::class, 'create'])->name('create');
    Route::post('/',             [SolarProjectController::class, 'store'])->name('store');
    Route::get('{project}/health-certificate', [SolarProjectController::class, 'healthCertificate'])->name('health-certificate');
    Route::get('{project}',      [SolarProjectController::class, 'show'])->name('show');
    Route::get('{project}/edit', [SolarProjectController::class, 'edit'])->name('edit');
    Route::put('{project}',      [SolarProjectController::class, 'update'])->name('update');

    // ── Installed Panels ──────────────────────────────────────────────────────
    Route::get('{project}/panels/create',      [InstalledPanelController::class, 'create'])->name('panels.create');
    Route::post('{project}/panels',            [InstalledPanelController::class, 'store'])->name('panels.store');
    Route::delete('{project}/panels/{panel}',  [InstalledPanelController::class, 'destroy'])->name('panels.destroy');

    // ── Installed Inverters ───────────────────────────────────────────────────
    Route::get('{project}/inverters/create',         [InstalledInverterController::class, 'create'])->name('inverters.create');
    Route::post('{project}/inverters',               [InstalledInverterController::class, 'store'])->name('inverters.store');
    Route::delete('{project}/inverters/{inverter}',  [InstalledInverterController::class, 'destroy'])->name('inverters.destroy');

    // ── Installed Batteries ───────────────────────────────────────────────────
    Route::get('{project}/batteries/create',         [InstalledBatteryController::class, 'create'])->name('batteries.create');
    Route::post('{project}/batteries',               [InstalledBatteryController::class, 'store'])->name('batteries.store');
    Route::delete('{project}/batteries/{battery}',   [InstalledBatteryController::class, 'destroy'])->name('batteries.destroy');
});

// ─── روت‌های پیمانکار ────────────────────────────────────────────────────────
Route::middleware(['web', 'auth'])->prefix('contractor/my-projects')->name('solar-plant-equipment.contractor.projects.')->group(function () {

    // لیست و جزئیات پروژه‌ها
    Route::get('/',                                    [ContractorProjectsController::class, 'index'])->name('index');
    Route::get('{project}',                            [ContractorProjectsController::class, 'show'])->name('show');

    // ویرایش فیلدهای مجاز توسط پیمانکار
    Route::put('{project}',                            [ContractorProjectsController::class, 'update'])->name('update');

    // اعلام آماده‌ی بازرسی
    Route::post('{project}/ready-for-inspection',      [ContractorProjectsController::class, 'readyForInspection'])->name('ready-for-inspection');

    // ── افزودن تجهیزات (همان کنترلرهای موجود، با مسیر پیمانکار) ─────────────
    Route::get('{project}/panels/create',      [InstalledPanelController::class, 'create'])->name('panels.create');
    Route::post('{project}/panels',            [InstalledPanelController::class, 'store'])->name('panels.store');
    Route::delete('{project}/panels/{panel}',  [InstalledPanelController::class, 'destroy'])->name('panels.destroy');

    Route::get('{project}/inverters/create',         [InstalledInverterController::class, 'create'])->name('inverters.create');
    Route::post('{project}/inverters',               [InstalledInverterController::class, 'store'])->name('inverters.store');
    Route::delete('{project}/inverters/{inverter}',  [InstalledInverterController::class, 'destroy'])->name('inverters.destroy');

    Route::get('{project}/batteries/create',         [InstalledBatteryController::class, 'create'])->name('batteries.create');
    Route::post('{project}/batteries',               [InstalledBatteryController::class, 'store'])->name('batteries.store');
    Route::delete('{project}/batteries/{battery}',   [InstalledBatteryController::class, 'destroy'])->name('batteries.destroy');
});
