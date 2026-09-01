<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FinancialCategoryController;
use App\Http\Controllers\FinancialDashboardController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\FinancialSubcategoryController;
use App\Http\Controllers\FinancialTransactionController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RootRedirectController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\WorshipFunctionController;
use App\Http\Controllers\WorshipSetController;
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

    // Módulo Financeiro - Todas as rotas com prefixo /financial/
    Route::prefix('financial')->name('financial.')->group(function () {
        // Dashboard financeiro
        Route::get('/dashboard', [FinancialDashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/dashboard/data', [FinancialDashboardController::class, 'getData'])->name('dashboard.data');

        // Categorias financeiras
        Route::resource('categories', FinancialCategoryController::class)->parameters(['categories' => 'financialCategory']);
        Route::get('/categories/subcategories', [FinancialCategoryController::class, 'getSubcategories'])->name('categories.subcategories');

        // Subcategorias financeiras
        Route::resource('subcategories', FinancialSubcategoryController::class)->parameters(['subcategories' => 'financialSubcategory']);

        // Transações financeiras
        Route::resource('transactions', FinancialTransactionController::class)->parameters(['transactions' => 'financialTransaction']);

        // Campanhas
        Route::resource('campaigns', CampaignController::class);

        // Relatórios financeiros
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [FinancialReportController::class, 'index'])->name('index');
            Route::get('/monthly', [FinancialReportController::class, 'monthly'])->name('monthly');
            Route::get('/annual/detailed', [FinancialReportController::class, 'annualDetailed'])->name('annual.detailed');
            Route::get('/annual/summary', [FinancialReportController::class, 'annualSummary'])->name('annual.summary');
        });
    });

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

    // Gestão de louvor - Funções
    Route::prefix('worship/functions')->name('worship-functions.')->group(function () {
        Route::get('/', [WorshipFunctionController::class, 'index'])->name('index');
        Route::get('/create', [WorshipFunctionController::class, 'create'])->name('create');
        Route::post('/', [WorshipFunctionController::class, 'store'])->name('store');
        Route::get('/{worship_function}/edit', [WorshipFunctionController::class, 'edit'])->name('edit');
        Route::put('/{worship_function}', [WorshipFunctionController::class, 'update'])->name('update');
        Route::delete('/{worship_function}', [WorshipFunctionController::class, 'destroy'])->name('destroy');
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
