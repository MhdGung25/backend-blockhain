<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlockchainLog;

class BlockchainController extends Controller
{
    public function storeHash(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validatedData = $request->validate([
            'hash' => 'required|string',
            'jenis' => 'required|string',
            'jumlah' => 'required|numeric',
            'deskripsi' => 'nullable|string',
        ]);

        $log = BlockchainLog::create([
            'user_id' => $user->id,
            'hash' => $validatedData['hash'],
            'jenis' => $validatedData['jenis'],
            'jumlah' => $validatedData['jumlah'],
            'deskripsi' => $validatedData['deskripsi'] ?? null,
        ]);

        return response()->json([
            'message' => 'Hash berhasil disimpan.',
            'data' => $log,
        ], 201);
    }

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
            'data' => $history,
        ], 200);
    }
}
