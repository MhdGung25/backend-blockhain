<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TujuanController extends Controller
{
    /**
     * Simpan data tujuan dari UMKM.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'umkm_id' => 'required|exists:usahas,id',
            'tujuan' => 'required|array|min:1',
            'tujuan.*' => 'string|max:255',
        ]);

        // Simpan setiap tujuan ke dalam tabel `tujuans`
        foreach ($data['tujuan'] as $item) {
            DB::table('tujuans')->insert([
                'umkm_id' => $data['umkm_id'],
                'tujuan' => $item,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'message' => 'Data tujuan berhasil disimpan.',
            'data' => $data['tujuan'],
        ], 200);
    }

    /**
     * Tampilkan semua data tujuan (opsional).
     */
    public function index()
    {
        $data = DB::table('tujuans')->get();

        return response()->json([
            'message' => 'Data tujuan berhasil ditampilkan.',
            'data' => $data,
        ], 200);
    }
}
