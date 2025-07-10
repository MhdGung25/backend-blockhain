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

        return response()->json([
            'success' => true,
            'message' => '✅ Data transaksi ditemukan',
            'data'    => $transaksi,
        ], 200);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'jenis'     => 'required|in:pemasukan,pengeluaran',
            'jumlah'    => 'required|numeric|min:1',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        $transaksi = Transaksi::create([
            'user_id'   => $user->id,
            'jenis'     => $validated['jenis'],
            'jumlah'    => $validated['jumlah'],
            'deskripsi' => $validated['deskripsi'] ?? '',
        ]);

        return response()->json([
            'success' => true,
            'message' => '✅ Transaksi berhasil disimpan',
            'data'    => $transaksi,
        ], 201);
    }
}
