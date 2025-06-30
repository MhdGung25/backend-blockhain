<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TujuanController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'tujuan' => 'required|array',
            'tujuan.*' => 'string|max:255',
        ]);

        // Simpan atau proses data sesuai kebutuhanmu

        return response()->json([
            'message' => 'Data tujuan berhasil disimpan.',
            'data' => $data,
        ]);
    }

    public function index()
    {
        return response()->json([
            'message' => 'Data tujuan ditampilkan di sini.',
            'data' => [],
        ]);
    }
}
