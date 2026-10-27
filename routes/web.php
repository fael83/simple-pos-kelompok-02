<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QuickCategoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('pos.create');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/pos', [TransactionController::class, 'create'])->name('pos.create');
    Route::post('/pos', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

    Route::middleware('role:admin')->group(function () {
        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('products', ProductController::class)->except(['show', 'destroy']);
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export/csv', [ReportController::class, 'exportCsv'])->name('reports.export.csv');
        Route::get('/reports/export/xlsx', [ReportController::class, 'exportExcel'])->name('reports.export.xlsx');
        Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
        Route::get('/import', [ReportController::class, 'importForm'])->name('import.create');
        Route::post('/import', [ReportController::class, 'import'])->name('import.store');

        Route::get('/quick-categories', [QuickCategoryController::class, 'index'])->name('quick-categories.index');
        Route::post('/quick-categories', [QuickCategoryController::class, 'store'])->name('quick-categories.store');
    });
});