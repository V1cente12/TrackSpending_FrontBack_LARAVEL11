<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InitialBalanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return redirect()->route('login');
})->middleware('guest');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

    Route::get('/select-currency', [CurrencyController::class, 'show'])
    ->name('select-currency');

    Route::post('/set-currency', [CurrencyController::class, 'store'])
    ->name('set-currency');

    Route::get('/initial-balance', [InitialBalanceController::class, 'show'])
    ->name('initial-balance');

    Route::post('/set-initial-balance', [InitialBalanceController::class, 'store'])
    ->name('set-initial-balance');

    Route::get('/select-category', [CategoryController::class, 'show'])
    ->name('select-category');

    Route::post('/set-category', [CategoryController::class, 'store'])
    ->name('set-category');

    Route::post('/transactions', [TransactionController::class, 'store'])
    ->name('transactions.store');

    Route::get('/category-transactions/{categoryId}', [TransactionController::class, 'getCategoryTransactions'])
    ->name('category-transactions');

    Route::get('/transactions/{period}', [TransactionController::class, 'getTransactionsByPeriod'])
    ->where('period', 'day|week|month|all')
    ->name('transactions.by-period');
});