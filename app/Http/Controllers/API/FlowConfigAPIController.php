<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Flow;
use Illuminate\Http\Request;

class FlowConfigAPIController extends Controller
{
    public function loadConfig($uniqid)
    {
        $flow = Flow::where('uniqid', $uniqid)->first();
        if (!$flow) {
            return response()->json(['error' => 'Flow not found'], 404);
        }

        // Generate exactly the properties the downloaded flow uses
        $config = [
            'uniqid' => $flow->uniqid,
            'flow_name' => $flow->name,
            'render_white_page' => $flow->render_white_page,
            'white_page_url' => $flow->white_page_url,
            'render_bot_page' => $flow->render_bot_page,
            'bot_page_url' => $flow->bot_page_url,
            'render_offer_page' => $flow->render_offer_page,
            'offer_page_url' => $flow->offer_page_url,
            'allowed_countries' => is_array($flow->allowed_countries) ? strtoupper(implode(',', $flow->allowed_countries)) : '',
            'block_vpn' => $flow->block_vpn,
            'block_no_referer' => $flow->block_no_referer,
            'allowed_params' => $flow->allowed_params,
            'acs' => $flow->acs,
            'blocker_bots' => $flow->blocker_bots,
            'lock_isp' => strtoupper(implode(",", explode("\n", str_replace("\r", "", $flow->lock_isp ?? '')))),
            'lock_referers' => strtoupper(implode(",", explode("\n", str_replace("\r", "", $flow->lock_referers ?? '')))),
            'lock_device' => is_array($flow->lock_device) ? strtoupper(implode(',', $flow->lock_device)) : '',
            'lock_browser' => is_array($flow->lock_browser) ? strtoupper(implode(',', $flow->lock_browser)) : '',
        ];

        // Ensure all boolean or scalar values are strings exactly as they are embedded in the stubs
        foreach ($config as $key => $val) {
            if (is_bool($val)) {
                $config[$key] = $val ? '1' : '0';
            } else {
                $config[$key] = (string)$val;
            }
        }

        $hash = md5(json_encode($config));

        return response()->json([
            'success' => true,
            'hash' => $hash,
            'config' => $config
        ]);
    }
}
