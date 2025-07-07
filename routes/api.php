<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\KeuanganController;
use App\Http\Controllers\Api\PengajuanController;
use App\Http\Controllers\Api\ProfilUMKMController;
use App\Http\Controllers\Api\TransaksiController;
use App\Http\Controllers\Api\BlockchainController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Endpoint untuk konsumsi aplikasi frontend (misalnya Flutter)
| Semua route yang membutuhkan autentikasi dibungkus dalam middleware 'auth:sanctum'
*/

// 🟡 AUTH - Register & Login (Public)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// 🔐 PROTECTED ROUTES - Hanya bisa diakses setelah login
Route::middleware('auth:sanctum')->group(function () {

    // 🔐 Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // 🔵 Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // 💰 Keuangan
    Route::get('/keuangan', [KeuanganController::class, 'index']);
    Route::post('/keuangan', [KeuanganController::class, 'store']);

    // 🟢 Transaksi
    Route::get('/transaksi', [TransaksiController::class, 'index']);
    Route::post('/transaksi', [TransaksiController::class, 'store']);

    // 🟣 Profil UMKM
    Route::get('/profil-umkm', [ProfilUMKMController::class, 'show']);
    Route::post('/profil-umkm', [ProfilUMKMController::class, 'update']);

    // 🔴 Pengajuan Modal
    Route::get('/pengajuan', [PengajuanController::class, 'status']);
    Route::post('/pengajuan', [PengajuanController::class, 'store']);

    // 🔗 Blockchain Hash Storage
    Route::post('/store-hash', [BlockchainController::class, 'storeHash']);

    // Tambahkan route untuk mendapatkan riwayat hash blockchain
    Route::get('/hash-history', [BlockchainController::class, 'hashHistory']);

    // 🧑‍💼 ADMIN ONLY - Hanya bisa diakses oleh user dengan role admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/pengajuan/all', [PengajuanController::class, 'index']);
        Route::put('/pengajuan/verifikasi/{id}', [PengajuanController::class, 'verifikasi']);

        Route::get('/admin-dashboard', function () {
            return response()->json(['message' => 'Khusus Admin']);
        });
    });
});
