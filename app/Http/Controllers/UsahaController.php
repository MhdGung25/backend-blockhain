<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Usaha;
use Illuminate\Http\Request;

class UsahaController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'jenis_usaha' => 'required|string|max:255',
            'tahun_berdiri' => 'required|date_format:Y',
            'alamat' => 'required|string',
        ]);

        $usaha = Usaha::create($validated);

        return response()->json([
            'message' => 'Data usaha berhasil disimpan',
            'data' => $usaha
        ], 201);
    }
}
