<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/halo', function () {
    return 'Halo dari Simple POS';
});

// Langkah 8 & 10: Bungkus dengan middleware auth & tambahkan route riwayat
Route::middleware('auth')->group(function () {
    Route::get('/pos', [TransactionController::class, 'create'])->name('pos.create');
    Route::post('/pos', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/pos/riwayat', [TransactionController::class, 'riwayat'])->name('pos.riwayat');
});