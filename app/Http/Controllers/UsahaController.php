<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usaha;

class UsahaController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama_usaha' => 'required|string',
            'jenis_usaha' => 'required|string',
            'tahun_berdiri' => 'required|string',
            'alamat' => 'required|string',
        ]);

        // Simpan data
        $usaha = Usaha::create($validated);

        return response()->json($usaha, 200);
    }
}
