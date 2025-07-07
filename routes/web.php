<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| 
| File ini hanya digunakan jika kamu memakai Laravel Blade View.
| Karena kamu pakai Flutter + Sanctum API, sebagian besar route di sini tidak diperlukan.
*/

Route::get('/', function () {
    return view('welcome');
});

// Jika suatu saat pakai Blade (opsional)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// 🚫 Tidak perlu route login/register berbasis web jika pakai Sanctum API
// Jangan pakai AuthController web karena akan error jika class tidak ada.
