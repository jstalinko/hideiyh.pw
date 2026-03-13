<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Flow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class VisitorLogAPIController extends Controller
{
    public function store(Request $request)
    {

        $domain = $request->header('X-HIDEIYH-DOMAIN') ?? $request->domain;
        $domainModel = Domain::where('domain', $domain)->first();
        if (!$domainModel) {
            return response()->json(['error' => 'Domain not found'], 404);
        }
        $config = json_decode($request->config, true);
        $flow = Flow::firstOrCreate(
        ['uniqid' => $request->uniqid],
        [
            'user_id' => $domainModel->user_id,
            'name' => 'wp-plugin-' . $domain,
            'render_white_page' => $config['render_white_page'],
            'white_page_url' => $config['white_page_url'],
            'render_bot_page' => $config['render_bot_page'],
            'bot_page_url' => $config['bot_page_url'],
            'render_offer_page' => $config['render_offer_page'],
            'offer_page_url' => $config['offer_page_url'],
            'allowed_countries' => $config['allowed_countries'],
            'block_vpn' => $config['block_vpn'],
            'block_no_referer' => $config['block_no_referer'],
            'allowed_params' => $config['allowed_params'],
            'acs' => $config['acs'],
            'blocker_bots' => $config['blocker_bots'],
            'lock_isp' => $config['lock_isp'],
            'lock_referers' => $config['lock_referers'],
            'lock_device' => $config['lock_device'],
            'lock_browser' => $config['lock_browser'],
            'active' => true,
        ]
        );

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