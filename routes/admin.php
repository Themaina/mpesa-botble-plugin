<?php

use Illuminate\Support\Facades\Route;
use Botble\Mpesa\Http\Controllers\SettingsController;

// Admin plugin routes
Route::group(['prefix' => 'admin/plugins/mpesa', 'middleware' => ['web', 'core']], function () {
    Route::get('settings', [SettingsController::class, 'index'])->name('mpesa.settings');
    Route::post('settings', [SettingsController::class, 'update'])->name('mpesa.settings.update');
});

// Another route for admin, if needed
Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => ['web', 'core', 'auth']], function () {
    Route::group(['prefix' => 'mpesa', 'as' => 'mpesa.'], function () {
        // Corrected this to use SettingsController
        Route::get('settings', [SettingsController::class, 'index'])->name('settings');
        Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
    });
});
