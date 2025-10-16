<?php

namespace App\Http\Controllers\API;

use App\Helpers\HelperBlocker;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Helpers\BlockerHelper;

class BlockerAPIController extends Controller
{

    public function __invoke(Request $request)
    {
       
        $ip = $request->ip;
        $userAgent = $request->ua;
        $referrer = $request->referrer ?? $request->header('referer');
        $requestTime = now()->toIso8601String();

        try {
          
            Validator::make(['ip' => $ip , 'ua'=>$userAgent], ['ip' => 'required' , 'ua' => 'required'])->validate();

          
            $botChecks = BlockerHelper::getBotChecks($ip, $userAgent, $referrer, true);

            
            $isBot = false;
            $reason = 'Clean';

            foreach ($botChecks as $key => $isDetected) {
                
                if ($isDetected) {
                    $isBot = true;
                    // Buat alasan yang deskriptif berdasarkan kunci dari hasil pengecekan.
                    $reason = 'Blocked by ' . str_replace('_', ' ', $key). ' detected bot';
                    break; // Hentikan loop setelah menemukan alasan pertama.
                }
            }

            // Langkah 4: Kembalikan respons sukses.
            return response()->json([
                'status' => 'success',
                'is_bot' => $isBot,
                'reason' => $reason,
                'ip' => $ip,
                'user_agent' => $userAgent,
                'request_time' => $requestTime,
            ],200,[],JSON_PRETTY_PRINT);

        } catch (ValidationException $e) {
            // Tangkap error validasi secara spesifik.
            return response()->json([
                'status' => 'error',
                'message' => 'The provided IP address or userAgent is invalid. ',
                'ip' => $ip,
                'user_agent' => $userAgent,
                'request_time' => $requestTime,
            ], 422,[],JSON_PRETTY_PRINT); // Unprocessable Entity

        } catch (\Throwable $e) {
            // Tangkap semua error lainnya (misal: koneksi gagal, helper error).
            Log::error('BlockerAPIController Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected server error occurred.',
                'ip' => $ip,
                'user_agent' => $userAgent,
                'request_time' => $requestTime,
            ], 500 , [],JSON_PRETTY_PRINT); // Internal Server Error
        }
    }
}