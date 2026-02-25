<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Flow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class VisitorLogAPIController extends Controller
{
    public function store(Request $request)
    {
        $flow = Flow::where('uniqid', $request->uniqid)->first();
        if (!$flow) {
            return response()->json(['error' => 'Flow not found'], 404);
        }

        $logData = [
            'flow_id' => $flow->id,
            'ip' => $request->ip ?? '0.0.0.0',
            'country' => $request->country ?? 'Unknown',
            'device' => $request->device ?? 'Unknown',
            'browser' => $request->browser ?? 'Unknown',
            'referer' => $request->referer ?? 'no-referer',
            'user_agent' => $request->user_agent ?? 'Unknown',
            'isp' => $request->isp ?? 'Unknown',
            'reason' => $request->reason ?? 'no reason',
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString()
        ];

        // Push ke Redis List untuk diproses di background worker/job
        Redis::rpush('visitor_logs_queue', json_encode($logData));

        return response()->json(['success' => true]);
    }
}
