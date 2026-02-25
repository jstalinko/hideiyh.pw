<?php

namespace App\Http\Controllers\API;

use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class GeolocationAPIController extends Controller
{
    /**
     * Handle the incoming request to get geolocation data for an IP address.
     * It uses caching to minimize external API calls.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {

        try {
            $validated = Validator::make($request->all(), [
                'ip' => 'sometimes|ip',
            ])->validate();

            $ip = $validated['ip'] ?? $request->ip;
        } catch (ValidationException $e) {
            return response()->json([

                'status' => 'error',
                'message' => 'Invalid IP address format provided.',
                'errors' => $e->errors()
            ], 422); // Unprocessable Entity
        }



        // Langkah 2: Buat cache key yang unik dan tentukan durasi cache.
        $cacheKey = "geolocation_{$ip}";
        $cacheDuration = now()->addMonth(); // Simpan cache selama 24 jam.

        // Langkah 3: Gunakan try-catch untuk menangani kegagalan koneksi ke API eksternal.
        try {
            // Cache::remember akan:
            // 1. Cek apakah ada data di cache dengan key '$cacheKey'.
            // 2. Jika ada, langsung kembalikan data tersebut.
            // 3. Jika tidak ada, jalankan closure (fungsi anonim) untuk mengambil data baru.
            // 4. Simpan hasil dari closure ke dalam cache, lalu kembalikan hasilnya.
            $data = Cache::remember($cacheKey, $cacheDuration, function () use ($ip) {

                $response = Http::timeout(5) // Timeout 5 detik untuk mencegah request yang terlalu lama
                    ->get("https://pro.ip-api.com/json/{$ip}?fields=21229567&key=LlYVGewz67LJuV8");

                // Jika request gagal (status code 4xx atau 5xx), lempar exception.
                // Exception ini akan ditangkap oleh blok catch di luar.
                if ($response->failed()) {
                    $response->throw();
                }

                $responseData = $response->json();

                // Jika ip-api.com mengembalikan status 'fail' (misal: IP private)
                if (isset($responseData['status']) && $responseData['status'] !== 'success') {
                    // Kita kembalikan null agar kegagalan ini tidak disimpan di cache.
                    return null;
                }

                // Jika berhasil, kembalikan data JSON.
                return $responseData;
            });

            // Jika $data null, berarti API eksternal gagal secara logis (status: 'fail').
            if ($data === null) {
                // Hapus cache yang mungkin berisi nilai null.
                Cache::forget($cacheKey);
                return response()->json(['status' => 'error', 'message' => "Geolocation data could not be retrieved for the specified IP: {$ip}."], 404);
            }

            // Langkah 4: Kembalikan data yang berhasil didapat (baik dari cache maupun API baru).
            return response()->json($data, 200);
        } catch (\Throwable $e) {
            // Tangkap semua jenis exception (koneksi gagal, timeout, server error, dll).
            // Catat error ke log untuk debugging.
            Log::error("Geolocation API request failed for IP {$ip}: " . $e->getMessage());

            // Kembalikan response error yang informatif.
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while communicating with the geolocation service.'
            ], 503); // Service Unavailable
        }
    }
}
