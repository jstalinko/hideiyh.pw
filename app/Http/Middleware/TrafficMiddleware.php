<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;

class TrafficMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $subscription = $user->activeSubscription;

        $domain = $request->header('X-HIDEIYH-DOMAIN');
        if (isset($request->domain)) {
            $domain = $request->domain;
        } elseif ($domain == null) {
            return response()->json(['status' => 'error', 'message' => 'No domain provided'], 201, [], JSON_PRETTY_PRINT);
        }

        $user = $request->user();

        $type = 'web';
        if ($request->is('api/blocker*') || $request->is('api/geolocation*')) {
            $type = 'api';
        }
        /** check domain */
        $domain_signature = sha1($user->id . $domain);
        // buat record domain kalau belum ada
        $dm = Domain::firstOrCreate(
            ['domain' => $domain, 'user_id' => $user->id],
            [
                'ip_server' => $request->ip(),
                'signature' => $domain_signature,
                'traffic_count' => 1,
                'connection_type' => $type,
            ]
        );

        // tambahkan traffic ke Redis
        if (Domain::where('user_id', $user->id)->count() >= $user->domain_quota) {
            return response()->json(['status' => 'error', 'message' => 'Domain quota exceeded. Your max domain is : ' . $user->domain_quota . '. Please remove your other domain in dashboard first'], 201, [], JSON_PRETTY_PRINT);
        }

        if (!$user->gold_member) {
            
            if ($user->domain_quota != $subscription->package->domain_quota) {
                $user->domain_quota = $subscription->package->domain_quota;
                $user->save();
            }
            if ($subscription->package->visitor_quota_perday === -1) {
                return $next($request);
            }

            if ($dm->traffic_count >= $subscription->package->visitor_quota_perday) {
                return response()->json(['status' => 'error', 'message' => 'Daily traffic quota exceeded.'], 429, [], JSON_PRETTY_PRINT);
            }
        }

        $key = "traffic:$domain_signature";
        Redis::incr($key);



        return $next($request);
    }
}
