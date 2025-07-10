<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlockchainLog;

class BlockchainController extends Controller
{
    /**
     * Menyimpan transaksi dengan hash yang di-generate secara otomatis.
     * (Digunakan saat simpan transaksi dari form Flutter)
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'jenis'     => 'required|in:pemasukan,pengeluaran',
            'jumlah'    => 'required|numeric|min:0.01',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        $log = BlockchainLog::create([
            'user_id'   => $user->id,
            'jenis'     => $validated['jenis'],
            'jumlah'    => $validated['jumlah'],
            'deskripsi' => $validated['deskripsi'],
            'hash'      => '0x' . bin2hex(random_bytes(10)), // generate hash dummy
        ]);

        return response()->json([
            'message' => 'Transaksi berhasil disimpan',
            'data'    => $log,
        ], 201);
    }

    /**
     * Menyimpan transaksi dengan hash yang dikirim dari Flutter (dari Blockchain asli).
     * (Digunakan saat sudah ada hash asli dari blockchain)
     */
    public function storeHash(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validatedData = $request->validate([
            'hash'      => 'required|string',
            'jenis'     => 'required|in:pemasukan,pengeluaran',
            'jumlah'    => 'required|numeric|min:0.01',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        $log = BlockchainLog::create([
            'user_id'   => $user->id,
            'hash'      => $validatedData['hash'],
            'jenis'     => $validatedData['jenis'],
            'jumlah'    => $validatedData['jumlah'],
            'deskripsi' => $validatedData['deskripsi'],
        ]);

        return response()->json([
            'message' => 'Hash berhasil disimpan.',
            'data'    => $log,
        ], 201);
    }

    /**
     * Mengambil 10 riwayat transaksi terakhir milik user yang login.
     */
    public function hashHistory(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $history = BlockchainLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'message' => 'Hash history ditemukan.',
            'data'    => $history,
        ], 200);
    }
}
