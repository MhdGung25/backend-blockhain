<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ✅ Import semua controller yang digunakan
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\UsahaController;
use App\Http\Controllers\TujuanController;
use App\Http\Controllers\UmkmProfileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 🔐 Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

// 🔑 Forgot password
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink']);

// 🏪 Usaha
Route::post('/usaha', [UsahaController::class, 'store']);

// 🎯 Tujuan
Route::get('/tujuan', [TujuanController::class, 'index']);
Route::post('/tujuan', [TujuanController::class, 'store']);

// 🧾 Profil UMKM (dilindungi auth:sanctum)
Route::middleware('auth:sanctum')->prefix('umkm')->group(function () {
    Route::get('/', [UmkmProfileController::class, 'apiIndex']);      
    Route::post('/', [UmkmProfileController::class, 'apiStore']);    
    Route::get('/{id}', [UmkmProfileController::class, 'apiShow']);  
    Route::put('/{id}', [UmkmProfileController::class, 'apiUpdate']);
    Route::delete('/{id}', [UmkmProfileController::class, 'apiDestroy']); 
});
