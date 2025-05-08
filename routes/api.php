<?php
use Illuminate\Support\Facades\Route;
use Botble\Mpesa\Http\Controllers\MpesaController;

Route::group(['prefix' => 'api/mpesa', 'middleware' => ['web']], function () {
    // C2B STK Push
    Route::post('stk-push', [MpesaController::class, 'stkPush'])->name('mpesa.stk.push');
    // C2B Simulation
    Route::post('simulate', [MpesaController::class, 'simulateC2B'])->name('mpesa.simulate');
    // B2C payment
    Route::post('b2c', [MpesaController::class, 'b2cPayment'])->name('mpesa.b2c');
    // B2B payment
    Route::post('b2b', [MpesaController::class, 'b2bPayment'])->name('mpesa.b2b');
    // Account balance
    Route::post('balance', [MpesaController::class, 'accountBalance'])->name('mpesa.balance');
    // Transaction status query
    Route::post('status', [MpesaController::class, 'transactionStatus'])->name('mpesa.status');
    // Reversal
    Route::post('reversal', [MpesaController::class, 'reversal'])->name('mpesa.reversal');
    // URL registration
    Route::post('register-urls', [MpesaController::class, 'registerURLs'])->name('mpesa.register');
    // Callback endpoints
    Route::post('callback', [MpesaController::class, 'handleCallback'])->name('mpesa.callback');
    Route::post('validation', [MpesaController::class, 'handleValidation'])->name('mpesa.validation');
});
