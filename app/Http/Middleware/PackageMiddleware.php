<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PackageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Dapatkan user yang sudah diautentikasi oleh ApikeyMiddleware
        $user = $request->user();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

       
        if ($user->gold_member) {
            return $next($request);
        }

    
        $subscription = $user->activeSubscription;

        if (!$subscription) {
            return response()->json(['status' => 'error', 'message' => 'No active subscription found.'], 403);
        }

     
        if ($request->is('api/blocker*')) {
            // Jika fitur API Blocker di paketnya false, blokir
            if (!$subscription->package->feature_api_blocker) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Your current package does not include access to the Blocker API.'
                ], 403); // 403 Forbidden
            }
        }

        // 7. Cek izin untuk Geolocation API
        if ($request->is('api/geolocation*')) {
            // Jika fitur API Geolocation di paketnya false, blokir
            if (!$subscription->package->feature_api_geolocation) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Your current package does not include access to the Geolocation API.'
                ], 403); // 403 Forbidden
            }
        }

        if($request->is('api/validate*'))
        {
            if(!$subscription->package->feature_acs_cloaking_script)
            {
                  return response()->json([
                    'status' => 'error',
                    'message' => 'Your current package does not include access to the AD-IYH CLOAKING SCRIPT (ACS)'
                ], 403); // 403 Forbidden
            }
        }

    
        return $next($request);
    }
}