<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Import semua controller yang digunakan
use App\Http\Controllers\UsahaController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\TujuanController; // ← Tambahkan baris ini

// 🔐 Auth
Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login'])->name('login');

// 🔑 Forgot password
Route::post('/forgot-password', [AuthenticationController::class, 'forgotPassword']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink']);

// 🏪 Usaha
Route::post('/usaha', [UsahaController::class, 'store']);

// 🎯 Tujuan
Route::post('/tujuan', [TujuanController::class, 'store']);
Route::get('/tujuan', [TujuanController::class, 'index']);
