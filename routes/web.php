<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RootRedirectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\FinancialCategoryController;
use App\Http\Controllers\FinancialTransactionController;
use App\Http\Controllers\FinancialSubcategoryController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\FinancialDashboardController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\WorshipSetController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Rota raiz
Route::get('/', RootRedirectController::class);

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'password.changed'])
    ->name('dashboard');

// Rotas protegidas por autenticação e verificação de senha alterada
Route::middleware(['auth', 'password.changed'])->group(function () {

    // Perfil do usuário
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Gestão de membros
    Route::resource('members', MemberController::class);
    Route::prefix('members/{member}')->group(function () {
        Route::get('files', [FileController::class, 'index'])->name('members.files.index');
        Route::post('files', [FileController::class, 'store'])->name('members.files.store');
        Route::delete('files/{file}', [FileController::class, 'destroy'])->name('members.files.destroy');
    });

    // Gestão de visitantes
    Route::resource('visitors', VisitorController::class);

    // Gestão de departamentos
    Route::resource('departments', DepartmentController::class);

    // Gestão financeira
    Route::resource('financial-categories', FinancialCategoryController::class);
    Route::get('/categories/subcategories', [FinancialCategoryController::class, 'getSubcategories'])->name('categories.subcategories');
    Route::resource('financial-transactions', FinancialTransactionController::class);

    // Subcategorias financeiras
    Route::prefix('subcategories')->name('subcategories.')->group(function () {
        Route::get('/', [FinancialSubcategoryController::class, 'index'])->name('index');
        Route::get('/create', [FinancialSubcategoryController::class, 'create'])->name('create');
        Route::post('/', [FinancialSubcategoryController::class, 'store'])->name('store');
        Route::get('/{financialSubcategory}/edit', [FinancialSubcategoryController::class, 'edit'])->name('edit');
        Route::put('/{financialSubcategory}', [FinancialSubcategoryController::class, 'update'])->name('update');
        Route::delete('/{financialSubcategory}', [FinancialSubcategoryController::class, 'destroy'])->name('destroy');
    });

    // Dashboard financeiro
    Route::get('/financial-dashboard', [FinancialDashboardController::class, 'index'])->name('financial-dashboard.index');
    Route::get('/financial-dashboard/data', [FinancialDashboardController::class, 'getData'])->name('financial-dashboard.data');

    // Relatórios financeiros
    Route::get('/reports/financial', [FinancialReportController::class, 'index'])->name('reports.financial.index');
    Route::get('/reports/financial/monthly', [FinancialReportController::class, 'monthly'])->name('reports.financial.monthly');
    Route::get('/reports/financial/annual/detailed', [FinancialReportController::class, 'annualDetailed'])->name('reports.financial.annual.detailed');
    Route::get('/reports/financial/annual/summary', [FinancialReportController::class, 'annualSummary'])->name('reports.financial.annual.summary');

    // Campanhas
    Route::resource('campaigns', CampaignController::class);

    // Gestão de louvor - Músicas
    Route::prefix('worship/songs')->name('songs.')->group(function () {
        Route::get('/', [SongController::class, 'index'])->name('index');
        Route::get('/create', [SongController::class, 'create'])->name('create');
        Route::post('/', [SongController::class, 'store'])->name('store');
        Route::get('/{song}', [SongController::class, 'show'])->name('show');
        Route::get('/{song}/edit', [SongController::class, 'edit'])->name('edit');
        Route::put('/{song}', [SongController::class, 'update'])->name('update');
        Route::delete('/{song}', [SongController::class, 'destroy'])->name('destroy');
    });

    // Gestão de louvor - Repertórios
    Route::prefix('worship/sets')->name('worship-sets.')->group(function () {
        Route::get('/', [WorshipSetController::class, 'index'])->name('index');
        Route::get('/create', [WorshipSetController::class, 'create'])->name('create');
        Route::post('/', [WorshipSetController::class, 'store'])->name('store');
        Route::get('/{worshipSet}', [WorshipSetController::class, 'show'])->name('show');
        Route::get('/{worshipSet}/edit', [WorshipSetController::class, 'edit'])->name('edit');
        Route::put('/{worshipSet}', [WorshipSetController::class, 'update'])->name('update');
        Route::delete('/{worshipSet}', [WorshipSetController::class, 'destroy'])->name('destroy');
        Route::get('/{worshipSet}/clone', [WorshipSetController::class, 'clone'])->name('clone');
    });

    // Gestão de Roles (apenas para administradores)
    Route::resource('roles', RoleController::class)->middleware(['role:administrador']);

    // Gestão de Permissions (apenas para administradores)
    Route::resource('permissions', PermissionController::class)->middleware(['role:administrador']);

    // Gestão de Users (apenas para administradores)
    Route::resource('users', UserController::class)->middleware(['role:administrador']);
});

// Rotas de autenticação
require __DIR__.'/auth.php';
