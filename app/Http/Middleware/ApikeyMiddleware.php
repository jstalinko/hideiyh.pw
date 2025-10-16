<?php

namespace App\Http\Middleware;

use App\Models\User; // Pastikan Anda mengimpor model User
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApikeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Ambil API key dari header request
        $apiKey = $request->header('X-HIDEIYH-APIKEY');

        // 2. Cek apakah header tersebut ada dan tidak kosong
        if (!$apiKey) {
            return response()->json([
                'status' => 'error',
                'message' => 'API Key is missing from headers.'
            ], 401); // 401 Unauthorized
        }

        // 3. Cari pengguna yang memiliki API key tersebut di database
        //    Gunakan cache untuk mempercepat pencarian berulang kali
        $cacheKey = 'user_by_apikey_' . $apiKey;
        $user = cache()->remember($cacheKey, now()->addMinutes(10), function () use ($apiKey) {
            return User::where('apikey', $apiKey)->first();
        });

        // 4. Jika tidak ada pengguna yang cocok, kembalikan error
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid API Key provided.'
            ], 401); // 401 Unauthorized
        }

        // 5. (Best Practice) Jika pengguna ditemukan, "autentikasikan" mereka untuk request ini
        //    Ini memungkinkan Anda menggunakan $request->user() di dalam controller.
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        // 6. Jika semua validasi lolos, lanjutkan request ke controller
        return $next($request);
    }
}