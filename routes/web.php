<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RootRedirectController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\FinancialCategoryController;
use App\Http\Controllers\FinancialTransactionController;
use App\Http\Controllers\FinancialSubcategoryController;
use App\Http\Controllers\FinancialReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', RootRedirectController::class);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('members', MemberController::class);
Route::resource('financial-categories', FinancialCategoryController::class);
Route::resource('financial-transactions', FinancialTransactionController::class);

Route::prefix('subcategorias')->group(function () {
    Route::get('/', [FinancialSubcategoryController::class, 'index'])->name('subcategories.index');
    Route::get('/create', [FinancialSubcategoryController::class, 'create'])->name('subcategories.create');
    Route::post('/', [FinancialSubcategoryController::class, 'store'])->name('subcategories.store');
    Route::get('/{financialSubcategory}/edit', [FinancialSubcategoryController::class, 'edit'])->name('subcategories.edit');
    Route::put('/{financialSubcategory}', [FinancialSubcategoryController::class, 'update'])->name('subcategories.update');
    Route::delete('/{financialSubcategory}', [FinancialSubcategoryController::class, 'destroy'])->name('subcategories.destroy');
});

Route::get('/relatorios/financeiro/mensal', FinancialReportController::class)->name('reports.financial.monthly');

require __DIR__.'/auth.php';
