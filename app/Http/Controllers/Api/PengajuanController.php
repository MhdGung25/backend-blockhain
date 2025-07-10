<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;

class PengajuanController extends Controller
{
    // ✅ GET semua pengajuan (hanya untuk admin)
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $pengajuans = Pengajuan::with('user')->latest()->get();

        return response()->json([
            'message' => 'Daftar semua pengajuan',
            'data'    => $pengajuans
        ], 200);
    }

    // ✅ PUT verifikasi pengajuan (Disetujui / Ditolak)
    public function verifikasi(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak'
        ]);

        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->status = $request->status;
        $pengajuan->save();

        return response()->json([
            'message' => "Pengajuan berhasil di{$request->status}",
            'data'    => $pengajuan
        ], 200);
    }

    // ✅ GET status pengajuan terbaru milik user yang sedang login
    public function status()
    {
        $pengajuan = Pengajuan::where('user_id', auth()->id())
            ->latest()
            ->first();

        if (!$pengajuan) {
        return response()->json([
            'message' => 'Belum ada pengajuan.',
            'data' => null
        ], 200); // atau tetap 404 jika kamu ingin frontend tangani sebagai "tidak ditemukan"
    }


        return response()->json([
            'message' => 'Status pengajuan ditemukan',
            'data'    => $pengajuan
        ], 200);
    }

    // ✅ POST buat pengajuan modal
    public function store(Request $request)
    {
        $request->validate([
            'jumlah'    => ['required', 'numeric', 'min:100000', 'max:50000000'],
            'alasan'    => ['required', 'string', 'min:10', 'max:255'],
            'bank'      => ['required', 'string', 'max:100'],
            'logo_url'  => ['required', 'string', 'max:255'],
            'tenor'     => ['required', 'integer', 'min:1', 'max:36'],
        ]);

        $pengajuan = Pengajuan::create([
            'user_id'   => auth()->id(),
            'jumlah'    => $request->jumlah,
            'alasan'    => $request->alasan,
            'bank'      => $request->bank,
            'logo_url'  => $request->logo_url,
            'tenor'     => $request->tenor,
            'status'    => 'Menunggu Verifikasi',
        ]);

        return response()->json([
            'message' => 'Pengajuan berhasil dikirim',
            'data'    => $pengajuan
        ], 201);
    }
}
