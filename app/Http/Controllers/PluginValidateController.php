<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\User;
use Illuminate\Http\Request;

class PluginValidateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $domain = isset($request->domain) ? $request->domain : null;
        $email = isset($request->email) ? $request->email : null;
        $ip_server = isset($request->ip_server) ? $request->ip_server : null;

        if ($domain == null || $email == null || $ip_server == null) {
            return response()->json(['success' => false, 'message' => 'Data not valid'], 200, [], JSON_PRETTY_PRINT);
        }

        $checkUser = User::where('email', $email)->exists();

        if ($checkUser) {
            $user = User::where('email', $email)->first();
            $sign = sha1($user->id.$domain);
            $dom = Domain::where('signature', $sign);

            if ($dom->exists()) {

                return response()->json(['success' => true, 'signature' => $sign, 'data' => $dom], 200, [], JSON_PRETTY_PRINT);

            } else {
                $countDomainUser = Domain::where('user_id', $user->id)->count();

                if ($countDomainUser >= $user->domain_quota) {
                
                    return response()->json(['success' => false, 'message' => 'Domain limit exceeded, Your max domain is : ' . $user->domain_quota . '. Please remove your other domain in dashboard first'], 200, [], JSON_PRETTY_PRINT);
                
                } else {
                
                    $create = Domain::create(['signature' => $sign, 'ip_server' => $ip_server, 'user_id' => $user->id, 'domain' => $domain]);
                    if ($create) {
                        return response()->json(['success' => true, 'signature' => $sign, 'data' => $dom], 200, [], JSON_PRETTY_PRINT);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Server error, cant registered domain'], 500, [], JSON_PRETTY_PRINT);
                    }
                }
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Email not registered ', 'email' => $email], 401, [], JSON_PRETTY_PRINT);
        }
    }
}
