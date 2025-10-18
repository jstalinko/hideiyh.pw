<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Helpers\BlockerHelper;
use Illuminate\Support\Facades\Cache; // 1. Impor Cache facade

class BlockerAPIController extends Controller
{
    public function __invoke(Request $request)
    {
        // Ambil input. Kita standarkan referrer yang null menjadi string kosong
        // agar cache key-nya konsisten.
        $ip = $request->ip;
        $userAgent = $request->ua;
        $referrer = $request->referrer ?? $request->header('referer') ?? '';

        // 2. Buat cache key yang unik berdasarkan semua input
        // Kita gunakan md5() agar key-nya aman dan ringkas
        $keyString = "ip:{$ip}|ua:{$userAgent}|ref:{$referrer}";
        $cacheKey = 'blocker_api_result_' . md5($keyString);

        // 3. Tentukan durasi cache (misal: 60 menit)
        $cacheDurationInMinutes = 60;

        // 4. Gunakan Cache::remember
        // - Jika $cacheKey ada di cache, langsung kembalikan isinya.
        // - Jika tidak, jalankan closure (fungsi anonim), simpan hasilnya di cache,
        //   lalu kembalikan hasilnya.
        return Cache::remember($cacheKey, $cacheDurationInMinutes, function () use ($ip, $userAgent, $referrer) {
            
            // 5. Seluruh logika asli Anda sekarang ada di dalam closure
            $requestTime = now()->toIso8601String();

            try {
                Validator::make(['ip' => $ip , 'ua'=>$userAgent], ['ip' => 'required' , 'ua' => 'required'])->validate();

                $botChecks = BlockerHelper::getBotChecks($ip, $userAgent, $referrer, true);

                $isBot = false;
                $reason = 'Clean';

                foreach ($botChecks as $key => $isDetected) {
                    if ($isDetected) {
                        $isBot = true;
                        $reason = 'Blocked by ' . str_replace('_', ' ', $key). ' detected bot';
                        break;
                    }
                }

                return response()->json([
                    'status' => 'success',
                    'is_bot' => $isBot,
                    'reason' => $reason,
                    'ip' => $ip,
                    'user_agent' => $userAgent,
                    'request_time' => $requestTime,
                ], 200, [], JSON_PRETTY_PRINT);

            } catch (ValidationException $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The provided IP address or userAgent is invalid. ',
                    'ip' => $ip,
                    'user_agent' => $userAgent,
                    'request_time' => $requestTime,
                ], 422, [], JSON_PRETTY_PRINT);

            } catch (\Throwable $e) {
                Log::error('BlockerAPIController Error: ' . $e->getMessage());

                return response()->json([
                    'status' => 'error',
                    'message' => 'An unexpected server error occurred.',
                    'ip' => $ip,
                    'user_agent' => $userAgent,
                    'request_time' => $requestTime,
                ], 500, [], JSON_PRETTY_PRINT);
            }
        });
    }
}