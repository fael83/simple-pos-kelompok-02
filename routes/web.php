<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', DashboardController::class)->name('dashboard');

// Kasir POS
// Menampilkan halaman POS dan daftar produk yang menggunakan pagination
Route::get('/pos', [TransactionController::class, 'create'])->name('pos.create');

// Memproses transaksi
Route::post('/pos', [TransactionController::class, 'store'])->name('transactions.store');

// Menampilkan daftar transaksi
Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

// Menampilkan detail transaksi
Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');

// Manajemen kategori
Route::resource('categories', CategoryController::class)->except('show');

// Manajemen produk
Route::resource('products', ProductController::class)->except(['show', 'destroy']);