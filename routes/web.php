<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InitialBalanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CurrencyController;

Route::get('/', function () {
    return redirect()->route('login');
})->middleware('guest');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/select-currency', [CurrencyController::class, 'show'])->name('select-currency');
    Route::post('/set-currency', [CurrencyController::class, 'store'])->name('set-currency');
    Route::get('/initial-balance', [InitialBalanceController::class, 'show'])->name('initial-balance');
    Route::post('/set-initial-balance', [InitialBalanceController::class, 'store'])->name('set-initial-balance');
});