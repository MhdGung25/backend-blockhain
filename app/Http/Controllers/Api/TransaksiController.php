<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;

class TransaksiController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $transaksi = Transaksi::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($transaksi, 200);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'jenis'     => 'required|in:pemasukan,pengeluaran',
            'jumlah'    => 'required|numeric',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        $transaksi = Transaksi::create([
            'user_id'   => $user->id,
            'jenis'     => $request->jenis,
            'jumlah'    => $request->jumlah,
            'deskripsi' => $request->deskripsi ?? '',
        ]);

        return response()->json([
            'message' => '✅ Transaksi berhasil disimpan',
            'data'    => $transaksi,
        ], 201);
    }
}
