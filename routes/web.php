<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GateScreenController;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TopUpController;
use App\Http\Controllers\TopUpRequestController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// =======================================
// AUTHENTICATION ROUTES
// =======================================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirect root to dashboard
Route::get('/', fn() => redirect('/dashboard'));

// =======================================
// ADMIN ROUTES (auth + admin middleware)
// =======================================
Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Students
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

    // Parkings
    Route::get('/parkings', [ParkingController::class, 'index'])->name('parkings.index');
    Route::delete('/parkings/all', [ParkingController::class, 'destroyAll'])->name('parkings.destroy-all');
    Route::delete('/parkings/{parking}', [ParkingController::class, 'destroy'])->name('parkings.destroy');

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::delete('/transactions/all', [TransactionController::class, 'destroyAll'])->name('transactions.destroy-all');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

    // Top-ups (Admin Direct)
    Route::get('/topups', [TopUpController::class, 'index'])->name('topups.index');
    Route::post('/topups', [TopUpController::class, 'store'])->name('topups.store');
    Route::get('/topups/search-users', [TopUpController::class, 'searchUsers'])->name('topups.search-users');

    // Top-up Requests (Student Approval Workflow)
    Route::get('/topup-requests', [TopUpRequestController::class, 'index'])->name('topup-requests.index');
    Route::post('/topup-requests/{topupRequest}/approve', [TopUpRequestController::class, 'approve'])->name('topup-requests.approve');
    Route::post('/topup-requests/{topupRequest}/reject', [TopUpRequestController::class, 'reject'])->name('topup-requests.reject');
});

// =======================================
// GATE DISPLAY SCREEN (auth + display middleware)
// =======================================
Route::middleware(['auth', 'display'])->group(function () {
    Route::get('/gate-screen', [GateScreenController::class, 'index'])->name('gate-screen');
});
