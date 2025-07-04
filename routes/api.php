<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ✅ Import semua controller
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\UsahaController;
use App\Http\Controllers\TujuanController;
use App\Http\Controllers\UmkmProfileController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 🔐 Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

// 🔑 Forgot Password
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink']);

// 🏪 Usaha
Route::post('/usaha', [UsahaController::class, 'store']);

// 🎯 Tujuan UMKM
Route::get('/tujuan', [TujuanController::class, 'index']);
Route::post('/tujuan', [TujuanController::class, 'store']);

// 🧾 Profil UMKM & Dashboard (dilindungi Sanctum)
Route::middleware('auth:sanctum')->prefix('umkm')->group(function () {
    // 📄 Profil UMKM
    Route::get('/', [UmkmProfileController::class, 'apiIndex']);
    Route::post('/', [UmkmProfileController::class, 'apiStore']);
    Route::get('/{id}', [UmkmProfileController::class, 'apiShow']);
    Route::put('/{id}', [UmkmProfileController::class, 'apiUpdate']);
    Route::delete('/{id}', [UmkmProfileController::class, 'apiDestroy']);

    // 📊 Dashboard UMKM
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
