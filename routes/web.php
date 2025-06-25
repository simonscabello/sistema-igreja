<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RootRedirectController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\FinancialCategoryController;
use App\Http\Controllers\FinancialTransactionController;
use App\Http\Controllers\FinancialSubcategoryController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\WorshipSetController;
use App\Http\Controllers\DepartmentController;
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
Route::resource('visitors', VisitorController::class);
Route::resource('departamentos', DepartmentController::class);
Route::resource('financial-categories', FinancialCategoryController::class);
Route::resource('financial-transactions', FinancialTransactionController::class);
Route::resource('campaigns', CampaignController::class);

Route::prefix('subcategorias')->group(function () {
    Route::get('/', [FinancialSubcategoryController::class, 'index'])->name('subcategories.index');
    Route::get('/create', [FinancialSubcategoryController::class, 'create'])->name('subcategories.create');
    Route::post('/', [FinancialSubcategoryController::class, 'store'])->name('subcategories.store');
    Route::get('/{financialSubcategory}/edit', [FinancialSubcategoryController::class, 'edit'])->name('subcategories.edit');
    Route::put('/{financialSubcategory}', [FinancialSubcategoryController::class, 'update'])->name('subcategories.update');
    Route::delete('/{financialSubcategory}', [FinancialSubcategoryController::class, 'destroy'])->name('subcategories.destroy');
});

Route::get('/relatorios/financeiro/mensal', FinancialReportController::class)->name('reports.financial.monthly');

Route::prefix('louvor/musicas')->name('songs.')->group(function () {
    Route::get('/', [SongController::class, 'index'])->name('index');
    Route::get('/create', [SongController::class, 'create'])->name('create');
    Route::post('/', [SongController::class, 'store'])->name('store');
    Route::get('/{song}', [SongController::class, 'show'])->name('show');
    Route::get('/{song}/edit', [SongController::class, 'edit'])->name('edit');
    Route::put('/{song}', [SongController::class, 'update'])->name('update');
    Route::delete('/{song}', [SongController::class, 'destroy'])->name('destroy');
});

Route::prefix('louvor/repertorios')->name('worship-sets.')->group(function () {
    Route::get('/', [WorshipSetController::class, 'index'])->name('index');
    Route::get('/create', [WorshipSetController::class, 'create'])->name('create');
    Route::post('/', [WorshipSetController::class, 'store'])->name('store');
    Route::get('/{worshipSet}', [WorshipSetController::class, 'show'])->name('show');
    Route::get('/{worshipSet}/edit', [WorshipSetController::class, 'edit'])->name('edit');
    Route::put('/{worshipSet}', [WorshipSetController::class, 'update'])->name('update');
    Route::delete('/{worshipSet}', [WorshipSetController::class, 'destroy'])->name('destroy');
    Route::get('/{worshipSet}/clone', [WorshipSetController::class, 'clone'])->name('clone');
});

require __DIR__.'/auth.php';
