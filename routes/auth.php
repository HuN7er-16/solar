<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use BehinInit\App\Http\Controllers\LoginController;
use BehinInit\App\Http\Controllers\OtpLoginController;
use BehinInit\App\Http\Controllers\PasswordResetController;
use BehinInit\App\Http\Controllers\RegisterUserController;
use BehinInit\App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisterUserController::class, 'store']);

    Route::get('login', [LoginController::class, 'create'])
                ->name('login');

    Route::post('login', [LoginController::class, 'store']);

    Route::post('otp/send', [OtpLoginController::class, 'send'])->name('otp.send');
    Route::get('otp/send', [OtpLoginController::class, 'view'])->name('otp.view');
    Route::post('otp/verify', [OtpLoginController::class, 'verify'])->name('otp.verify');
});

Route::middleware('auth')->group(function () {
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('logout', [LoginController::class, 'destroy'])
                ->name('logout');

    Route::get('setup-name', [OtpLoginController::class, 'setupNameView'])->name('otp.setup-name');
    Route::post('setup-name', [OtpLoginController::class, 'setupNameStore'])->name('otp.setup-name.store');
});
