<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UmkmProfileController extends Controller
{
    /**
     * Display a listing of the resource for web view
     */
    public function index()
    {
        try {
            $umkmProfiles = DB::table('umkm_profiles')
                ->where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get();
            
            return view('umkm.profile.index', compact('umkmProfiles'));
        } catch (\Exception $e) {
            Log::error('Error fetching UMKM profiles: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat data profil UMKM');
        }
    }

    /**
     * Show the form for creating a new resource
     */
    public function create()
    {
        $jenisUsahaOptions = [
            'Makanan',
            'Fashion',
            'Jasa',
            'Retail',
            'Lainnya'
        ];
        
        return view('umkm.profile.create', compact('jenisUsahaOptions'));
    }

    /**
     * Store a newly created resource in storage
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_usaha' => 'required|string|max:255',
            'jenis_usaha' => 'required|string|max:100',
            'deskripsi' => 'required|string|max:1000',
            'alamat' => 'required|string|max:500',
            'no_telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'tahun_berdiri' => 'required|integer|min:1900|max:' . date('Y'),
            'jumlah_karyawan' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::table('umkm_profiles')->insert([
                'user_id' => auth()->id(),
                'nama_usaha' => $request->nama_usaha,
                'jenis_usaha' => $request->jenis_usaha,
                'deskripsi' => $request->deskripsi,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'email' => $request->email,
                'tahun_berdiri' => $request->tahun_berdiri,
                'jumlah_karyawan' => $request->jumlah_karyawan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('umkm.profile')->with('success', 'Profil UMKM berhasil dibuat');
        } catch (\Exception $e) {
            Log::error('Error creating UMKM profile: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat profil UMKM');
        }
    }

    /**
     * Display the specified resource
     */
    public function show($id)
    {
        try {
            $umkmProfile = DB::table('umkm_profiles')
                ->where('id', $id)
                ->where('user_id', auth()->id())
                ->first();

            if (!$umkmProfile) {
                return redirect()->route('umkm.profile')->with('error', 'Profil UMKM tidak ditemukan');
            }

            return view('umkm.profile.show', compact('umkmProfile'));
        } catch (\Exception $e) {
            Log::error('Error showing UMKM profile: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat profil UMKM');
        }
    }

    /**
     * Show the form for editing the specified resource
     */
    public function edit($id)
    {
        try {
            $umkmProfile = DB::table('umkm_profiles')
                ->where('id', $id)
                ->where('user_id', auth()->id())
                ->first();

            if (!$umkmProfile) {
                return redirect()->route('umkm.profile')->with('error', 'Profil UMKM tidak ditemukan');
            }

            $jenisUsahaOptions = [
                'Makanan',
                'Fashion',
                'Jasa',
                'Retail',
                'Lainnya'
            ];

            return view('umkm.profile.edit', compact('umkmProfile', 'jenisUsahaOptions'));
        } catch (\Exception $e) {
            Log::error('Error editing UMKM profile: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat form edit');
        }
    }

    /**
     * Update the specified resource in storage
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_usaha' => 'required|string|max:255',
            'jenis_usaha' => 'required|string|max:100',
            'deskripsi' => 'required|string|max:1000',
            'alamat' => 'required|string|max:500',
            'no_telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'tahun_berdiri' => 'required|integer|min:1900|max:' . date('Y'),
            'jumlah_karyawan' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $affected = DB::table('umkm_profiles')
                ->where('id', $id)
                ->where('user_id', auth()->id())
                ->update([
                    'nama_usaha' => $request->nama_usaha,
                    'jenis_usaha' => $request->jenis_usaha,
                    'deskripsi' => $request->deskripsi,
                    'alamat' => $request->alamat,
                    'no_telepon' => $request->no_telepon,
                    'email' => $request->email,
                    'tahun_berdiri' => $request->tahun_berdiri,
                    'jumlah_karyawan' => $request->jumlah_karyawan,
                    'updated_at' => now(),
                ]);

            if ($affected === 0) {
                return redirect()->back()->with('error', 'Profil UMKM tidak ditemukan atau tidak dapat diupdate');
            }

            return redirect()->route('umkm.profile')->with('success', 'Profil UMKM berhasil diupdate');
        } catch (\Exception $e) {
            Log::error('Error updating UMKM profile: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengupdate profil UMKM');
        }
    }

    /**
     * Remove the specified resource from storage
     */
    public function destroy($id)
    {
        try {
            $affected = DB::table('umkm_profiles')
                ->where('id', $id)
                ->where('user_id', auth()->id())
                ->delete();

            if ($affected === 0) {
                return redirect()->back()->with('error', 'Profil UMKM tidak ditemukan');
            }

            return redirect()->route('umkm.profile')->with('success', 'Profil UMKM berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting UMKM profile: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus profil UMKM');
        }
    }

    // =================
    // API METHODS
    // =================

    /**
     * API: Get all UMKM profiles for current user
     */
    public function apiIndex(): JsonResponse
    {
        try {
            $umkmProfiles = DB::table('umkm_profiles')
                ->where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $umkmProfiles,
                'message' => 'Data berhasil dimuat'
            ]);
        } catch (\Exception $e) {
            Log::error('API Error fetching UMKM profiles: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data profil UMKM',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Store a newly created UMKM profile
     */
    public function apiStore(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_usaha' => 'required|string|max:255',
            'jenis_usaha' => 'required|string|max:100',
            'deskripsi' => 'required|string|max:1000',
            'alamat' => 'required|string|max:500',
            'no_telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'tahun_berdiri' => 'required|integer|min:1900|max:' . date('Y'),
            'jumlah_karyawan' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $id = DB::table('umkm_profiles')->insertGetId([
                'user_id' => auth()->id(),
                'nama_usaha' => $request->nama_usaha,
                'jenis_usaha' => $request->jenis_usaha,
                'deskripsi' => $request->deskripsi,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'email' => $request->email,
                'tahun_berdiri' => $request->tahun_berdiri,
                'jumlah_karyawan' => $request->jumlah_karyawan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $umkmProfile = DB::table('umkm_profiles')->where('id', $id)->first();

            return response()->json([
                'success' => true,
                'data' => $umkmProfile,
                'message' => 'Profil UMKM berhasil dibuat'
            ], 201);
        } catch (\Exception $e) {
            Log::error('API Error creating UMKM profile: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat profil UMKM',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Display the specified UMKM profile
     */
    public function apiShow($id): JsonResponse
    {
        try {
            $umkmProfile = DB::table('umkm_profiles')
                ->where('id', $id)
                ->where('user_id', auth()->id())
                ->first();

            if (!$umkmProfile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil UMKM tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $umkmProfile,
                'message' => 'Data berhasil dimuat'
            ]);
        } catch (\Exception $e) {
            Log::error('API Error showing UMKM profile: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat profil UMKM',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Update the specified UMKM profile
     */
    public function apiUpdate(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_usaha' => 'required|string|max:255',
            'jenis_usaha' => 'required|string|max:100',
            'deskripsi' => 'required|string|max:1000',
            'alamat' => 'required|string|max:500',
            'no_telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'tahun_berdiri' => 'required|integer|min:1900|max:' . date('Y'),
            'jumlah_karyawan' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $affected = DB::table('umkm_profiles')
                ->where('id', $id)
                ->where('user_id', auth()->id())
                ->update([
                    'nama_usaha' => $request->nama_usaha,
                    'jenis_usaha' => $request->jenis_usaha,
                    'deskripsi' => $request->deskripsi,
                    'alamat' => $request->alamat,
                    'no_telepon' => $request->no_telepon,
                    'email' => $request->email,
                    'tahun_berdiri' => $request->tahun_berdiri,
                    'jumlah_karyawan' => $request->jumlah_karyawan,
                    'updated_at' => now(),
                ]);

            if ($affected === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil UMKM tidak ditemukan atau tidak ada perubahan'
                ], 404);
            }

            $umkmProfile = DB::table('umkm_profiles')
                ->where('id', $id)
                ->where('user_id', auth()->id())
                ->first();

            return response()->json([
                'success' => true,
                'data' => $umkmProfile,
                'message' => 'Profil UMKM berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            Log::error('API Error updating UMKM profile: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate profil UMKM',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Remove the specified UMKM profile
     */
    public function apiDestroy($id): JsonResponse
    {
        try {
            $affected = DB::table('umkm_profiles')
                ->where('id', $id)
                ->where('user_id', auth()->id())
                ->delete();

            if ($affected === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil UMKM tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Profil UMKM berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('API Error deleting UMKM profile: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus profil UMKM',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}