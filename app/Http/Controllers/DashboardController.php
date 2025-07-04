<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user(); // ambil user yang sedang login (pakai Sanctum)

        // Ambil total pemasukan berdasarkan user
        $totalPemasukan = DB::table('transaksis')
            ->where('user_id', $user->id)
            ->where('jenis', 'Pemasukan')
            ->sum('jumlah');

        // Ambil total pengeluaran berdasarkan user
        $totalPengeluaran = DB::table('transaksis')
            ->where('user_id', $user->id)
            ->where('jenis', 'Pengeluaran')
            ->sum('jumlah');

        // Hitung saldo bersih
        $saldo = $totalPemasukan - $totalPengeluaran;

        return response()->json([
            'total_pemasukan' => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'saldo' => $saldo,
        ]);
    }
}
