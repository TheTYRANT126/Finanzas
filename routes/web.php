<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SavingsGoalController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::pattern('transaction', '[0-9]+');
Route::pattern('budget', '[0-9]+');
Route::pattern('goal', '[0-9]+');

Route::middleware('guest')->group(function () {
    Route::view('/entrar', 'auth.login')->name('login');
    Route::post('/entrar', [AuthController::class, 'login'])->middleware('throttle:8,1,saldo-login:')->name('login.store');
    Route::view('/registro', 'auth.register')->name('register');
    Route::post('/registro', [AuthController::class, 'register'])->middleware('throttle:5,1,saldo-register:')->name('register.store');
});

Route::middleware(['auth', 'auth.session'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::post('/salir', [AuthController::class, 'logout'])->name('logout');
    Route::get('/reportes', ReportController::class)->name('reports');
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/perfil/contrasena', [ProfileController::class, 'password'])->name('profile.password');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('movimientos', TransactionController::class)->except('show')
        ->parameters(['movimientos' => 'transaction'])->names('transactions')
        ->middlewareFor(['edit', 'update'], 'can:update,transaction')->middlewareFor('destroy', 'can:delete,transaction');
    Route::resource('presupuestos', BudgetController::class)->except('show')
        ->parameters(['presupuestos' => 'budget'])->names('budgets')
        ->middlewareFor(['edit', 'update'], 'can:update,budget')->middlewareFor('destroy', 'can:delete,budget');
    Route::resource('metas', SavingsGoalController::class)
        ->parameters(['metas' => 'goal'])->names('goals')
        ->middlewareFor('show', 'can:view,goal')->middlewareFor(['edit', 'update'], 'can:update,goal')
        ->middlewareFor('destroy', 'can:delete,goal');
    Route::post('/metas/{goal}/abonos', [ContributionController::class, 'store'])->name('goals.contributions.store');
    Route::delete('/metas/{goal}/abonos/{contribution}', [ContributionController::class, 'destroy'])
        ->whereNumber('contribution')->name('goals.contributions.destroy');
});
