<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthWithSignatureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $email = $request->email;
        $cacheKey = 'user_by_email_' . $email;
        $user = cache()->remember($cacheKey, now()->addMinutes(10), function () use ($email) {
            return \App\Models\User::where('email', $email)->first();
        });
         if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Email provided.'
            ], 401); 
        }
        $request->setUserResolver(function () use ($user) {
            return $user;
        });


        return $next($request);
    }
}
