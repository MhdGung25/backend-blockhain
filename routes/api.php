<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsahaController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ForgotPasswordController;

//auth
Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login'])->name('login');
//forgot_password
Route::post('/forgot-password', [AuthenticationController::class, 'forgotPassword']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink']);


//usaha
Route::post('/usaha', [UsahaController::class, 'store']);

