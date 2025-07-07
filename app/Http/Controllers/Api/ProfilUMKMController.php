<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UMKM;

class ProfilUMKMController extends Controller
{
    /**
     * Tampilkan data profil UMKM user yang sedang login
     */
    public function show()
    {
        $userId = auth()->id();

        $profil = UMKM::where('user_id', $userId)->first();

        if (!$profil) {
            return response()->json([
                'message' => 'Profil UMKM belum tersedia',
            ], 404);
        }

        return response()->json([
            'message' => 'Profil UMKM ditemukan',
            'data'    => $profil
        ], 200);
    }

    /**
     * Simpan atau update data profil UMKM
     */
    public function update(Request $request)
    {
        $userId = auth()->id();

        $request->validate([
            'nama_usaha'     => 'required|string|max:255',
            'jenis_usaha'    => 'required|string|max:255',
            'tahun_berdiri'  => 'required|numeric|digits:4',
            'alamat'         => 'required|string',
        ]);

        $profil = UMKM::updateOrCreate(
            ['user_id' => $userId],
            [
                'nama_usaha'    => $request->nama_usaha,
                'jenis_usaha'   => $request->jenis_usaha,
                'tahun_berdiri' => $request->tahun_berdiri,
                'alamat'        => $request->alamat,
            ]
        );

        return response()->json([
            'message' => 'Profil UMKM berhasil disimpan',
            'data'    => $profil
        ], 200);
    }
}
