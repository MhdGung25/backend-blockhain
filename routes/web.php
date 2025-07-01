<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UmkmProfileController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Dashboard Routes (Protected)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // UMKM Profile Routes
    Route::get('/umkm/profile', [UmkmProfileController::class, 'index'])->name('umkm.profile');
    Route::get('/umkm/profile/create', [UmkmProfileController::class, 'create'])->name('umkm.profile.create');
    Route::post('/umkm/profile', [UmkmProfileController::class, 'store'])->name('umkm.profile.store');
    Route::get('/umkm/profile/{id}/edit', [UmkmProfileController::class, 'edit'])->name('umkm.profile.edit');
    Route::put('/umkm/profile/{id}', [UmkmProfileController::class, 'update'])->name('umkm.profile.update');
    Route::delete('/umkm/profile/{id}', [UmkmProfileController::class, 'destroy'])->name('umkm.profile.destroy');
});

// API Routes for AJAX calls
Route::prefix('api')->middleware('auth')->group(function () {
    Route::prefix('usaha')->group(function () {
        Route::post('/umkm/create', [UmkmProfileController::class, 'apiStore'])->name('api.umkm.create');
        Route::get('/umkm/{id}', [UmkmProfileController::class, 'apiShow'])->name('api.umkm.show');
        Route::put('/umkm/{id}/update', [UmkmProfileController::class, 'apiUpdate'])->name('api.umkm.update');
        Route::delete('/umkm/{id}/delete', [UmkmProfileController::class, 'apiDestroy'])->name('api.umkm.delete');
        Route::get('/umkm/list', [UmkmProfileController::class, 'apiIndex'])->name('api.umkm.list');
    });
});