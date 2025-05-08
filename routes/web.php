<?php

use Illuminate\Support\Facades\Route;
use Botble\Base\Facades\BaseHelper;
use Botble\Mpesa\Http\Controllers\SettingsController;
use Botble\Mpesa\Http\Controllers\MpesaController;

// Admin panel routes
Route::group([
    'prefix' => BaseHelper::getAdminPrefix() . '/plugins/mpesa',
    'middleware' => ['web', 'core', 'auth'],
], function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('mpesa.settings');
    Route::post('/settings', [SettingsController::class, 'update'])->name('mpesa.settings.update');
});

// Public callback or webhook
Route::group(['middleware' => ['web', 'core']], function () {
    Route::post('/mpesa/callback', [MpesaController::class, 'handleCallback'])->name('mpesa.callback');
});
