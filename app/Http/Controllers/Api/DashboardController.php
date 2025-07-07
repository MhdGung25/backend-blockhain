<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'saldo'       => 5200000,
            'pemasukan'   => 6200000,
            'pengeluaran' => 1000000,
        ], 200);
    }
}
