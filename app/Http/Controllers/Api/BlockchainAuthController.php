<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use kornrunner\Keccak;
use Elliptic\EC;

class BlockchainAuthController extends Controller
{
    public function requestMessage(Request $request)
    {
        $address = strtolower($request->input('address'));

        if (empty($address)) {
            return response()->json(['message' => 'Address is required.'], 422);
        }

        $nonce = bin2hex(random_bytes(16));
        $message = "Login confirmation: " . $nonce;

        Cache::put("login_nonce_{$address}", $nonce, now()->addMinutes(5));

        return response()->json(['message' => $message]);
    }

    public function verifySignature(Request $request)
    {
        $address   = strtolower($request->input('address'));
        $signature = $request->input('signature');
        $message   = $request->input('message');

        if (empty($address) || empty($signature) || empty($message)) {
            return response()->json(['message' => 'Data tidak lengkap.'], 422);
        }

        $cachedNonce = Cache::get("login_nonce_{$address}");

        if (!$cachedNonce || !str_contains($message, $cachedNonce)) {
            return response()->json(['message' => 'Message tidak valid atau expired.'], 401);
        }

        $msg = "\x19Ethereum Signed Message:\n" . strlen($message) . $message;
        $msgHash = Keccak::hash($msg, 256);

        try {
            $signature = ltrim($signature, '0x');
            $r = substr($signature, 0, 64);
            $s = substr($signature, 64, 64);
            $v = hexdec(substr($signature, 128, 2));

            if ($v >= 27) {
                $v -= 27;
            }

            $ec = new EC('secp256k1');
            $publicKey = $ec->recoverPubKey($msgHash, [
                'r' => '0x' . $r,
                's' => '0x' . $s
            ], $v);

            $pubKeyEncoded = $publicKey->encode('hex');
            $pubKeyWithoutPrefix = substr($pubKeyEncoded, 2); // Remove uncompressed prefix
            $pubKeyHash = Keccak::hash(hex2bin($pubKeyWithoutPrefix), 256);
            $recoveredAddress = '0x' . substr($pubKeyHash, -40);

            if (strtolower($recoveredAddress) !== $address) {
                return response()->json(['message' => 'Signature tidak valid.'], 401);
            }

            Cache::forget("login_nonce_{$address}");

            return response()->json(['message' => 'Signature valid.', 'address' => $recoveredAddress]);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Gagal memproses signature.',
                'error' => $e->getMessage()
            ], 401);
        }
    }
}
