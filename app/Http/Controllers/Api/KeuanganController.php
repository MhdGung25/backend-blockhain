<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keuangan;

class KeuanganController extends Controller
{
    /**
     * Simpan data keuangan (POST /api/keuangan)
     */
    public function store(Request $request)
    {
        $request->validate([
            'history' => 'required|array',
            'history.*.tanggal' => 'required|date',
            'history.*.jenis' => 'required|in:pemasukan,pengeluaran',
            'history.*.jumlah' => 'required|numeric',
        ]);

        $userId = auth()->id();

        foreach ($request->history as $item) {
            Keuangan::create([
                'user_id' => $userId,
                'tanggal' => $item['tanggal'],
                'jenis' => $item['jenis'],
                'jumlah' => $item['jumlah'],
            ]);
        }

        return response()->json([
            'message' => '✅ Data keuangan berhasil disimpan',
        ], 201);
    }

    /**
     * Tampilkan data keuangan (GET /api/keuangan)
     */
    public function index()
    {
        $userId = auth()->id();

        $data = Keuangan::where('user_id', $userId)->get();

        $pemasukan = $data->where('jenis', 'pemasukan')->sum('jumlah');
        $pengeluaran = $data->where('jenis', 'pengeluaran')->sum('jumlah');

        return response()->json([
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'history' => $data,
        ]);
    }
}
