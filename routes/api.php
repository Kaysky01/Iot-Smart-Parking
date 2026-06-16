<?php

use App\Http\Controllers\Api\DeviceStatusController;
use App\Http\Controllers\Api\ParkingScanController;
use App\Http\Controllers\Api\StudentAuthController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\TopUpController;
use App\Http\Controllers\TopUpRequestController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ESP32 RFID Scan Endpoint
|--------------------------------------------------------------------------
| POST /api/scan  { "uid": "RFID_UID" }
| This is the primary endpoint for IoT device communication.
*/
Route::post('/scan', [ParkingScanController::class, 'scan']);

/*
|--------------------------------------------------------------------------
| Printer Status
|--------------------------------------------------------------------------
| GET  /api/device-status  — Frontend polls to check Printer status
*/
Route::get('/device-status', [DeviceStatusController::class, 'status']);

/*
|--------------------------------------------------------------------------
| Dashboard API (for real-time UI refresh)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'apiData']);
Route::get('/users', [UserController::class, 'apiList']);
Route::get('/parkings', [ParkingController::class, 'apiList']);
Route::get('/transactions', [TransactionController::class, 'apiList']);
Route::get('/topups', [TopUpController::class, 'apiList']);
Route::get('/topup-requests', [TopUpRequestController::class, 'apiList']);
Route::get('/students', [\App\Http\Controllers\StudentController::class, 'apiList']);

/*
|--------------------------------------------------------------------------
| Student Authentication API (Public)
|--------------------------------------------------------------------------
*/
Route::post('/student/login', [StudentAuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Student Mobile API (Sanctum Protected)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->prefix('student')->group(function () {
    Route::post('/logout', [StudentAuthController::class, 'logout']);
    Route::get('/profile', [StudentController::class, 'profile']);
    Route::patch('/profile', [StudentController::class, 'updateProfile']);
    Route::put('/profile', [StudentController::class, 'updateProfile']);
    Route::post('/change-password', [StudentController::class, 'changePassword']);
    Route::get('/balance', [StudentController::class, 'balance']);
    Route::get('/parking-history', [StudentController::class, 'parkingHistory']);
    Route::get('/transactions', [StudentController::class, 'transactions']);
    Route::get('/topups', [StudentController::class, 'topups']);
    Route::post('/topups', [StudentController::class, 'createTopup']);
    Route::get('/notifications', [StudentController::class, 'notifications']);
    Route::post('/notifications/mark-read', [StudentController::class, 'markNotificationsRead']);
});
