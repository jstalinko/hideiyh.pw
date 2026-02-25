<?php

/**
 * this code generate by:
 *  __   __  ___   ______   _______  ___   __   __  __   __        _______  _     _ 
 * |  | |  ||   | |      | |       ||   | |  | |  ||  | |  |      |       || | _ | |
 * |  |_|  ||   | |  _    ||    ___||   | |  |_|  ||  |_|  |      |    _  || || || |
 * |       ||   | | | |   ||   |___ |   | |       ||       |      |   |_| ||       |
 * |       ||   | | |_|   ||    ___||   | |_     _||       | ___  |    ___||       |
 * |   _   ||   | |       ||   |___ |   |   |   |  |   _   ||   | |   |    |   _   |
 * |__| |__||___| |______| |_______||___|   |___|  |__| |__||___| |___|    |__| |__|
 * 
 * ==================================================================================
 * | Secure Your Links with Built-in Bot Detection API
 * ==================================================================================
 */
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

class HideiyhFlow
{
    protected static $API_BASE_URL = 'https://hideiyh.pw/api';
    protected static $API_KEY = '{API_KEY}';

    public static function domain()
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
    public static function platform($method = 'cat')
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $platform = 'Unknown';
        $os_array = array(
            '/windows nt 10/i'      =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile',
            '/FBA[NV]|instagram/i'  =>  'fbbrowser'
        );
        foreach ($os_array as $regex => $value) {
            if (preg_match($regex, $user_agent)) {
                $platform = $value;
            }
        }
        if ($method == 'device') {
            if (preg_match('/windows|mac|linux/i', $platform)) {
                $platform = 'desktop';
            }
            if (preg_match('/iphone|ipod|ipad|android/i', $platform)) {
                $platform = 'mobile';
            }
            if (preg_match('/FBA[NV]|instagram/i', $platform)) {
                $platform = 'fbbrowser';
            }
            if (preg_match('/tablet|tab|ipad/i', $platform)) {
                $platform = 'tablet';
            }
        } elseif ($method == 'platform') {
            if (preg_match('/windows/i', $platform)) {
                $platform = 'windows';
            }
            if (preg_match('/mac/i', $platform)) {
                $platform = 'macos';
            }
            if (preg_match('/android/i', $platform)) {
                $platform = 'android';
            }
            if (preg_match('/linux/i', $platform)) {
                $platform = 'linux';
            }
            if (preg_match('/ubuntu/i', $platform)) {
                $platform = 'ubuntu';
            }
            if (preg_match('/blackberry/i', $platform)) {
                $platform = 'blackberry';
            }
            if (preg_match('/FBA[NV]|instagram/i', $platform)) {
                $platform = 'fbbrowser';
            }
        } else {
            $platform = $platform;
        }

        return strtoupper($platform);
    }
    public static function getBrowser()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $browser   = "Unknown Browser";

        if (empty($userAgent)) {
            return $browser;
        }

        if (preg_match('/Edg/i', $userAgent)) {
            $browser = "Edge";
        } elseif (preg_match('/OPR|Opera/i', $userAgent)) {
            $browser = "Opera";
        } elseif (preg_match('/Chrome/i', $userAgent)) {
            $browser = "Chrome";
        } elseif (preg_match('/Safari/i', $userAgent) && !preg_match('/Chrome/i', $userAgent)) {
            $browser = "Safari";
        } elseif (preg_match('/Firefox/i', $userAgent)) {
            $browser = "Firefox";
        } elseif (preg_match('/MSIE|Trident/i', $userAgent)) {
            $browser = "IE";
        } elseif (preg_match('/FBA[NV]|instagram/i', $userAgent)) {
            $browser = "FBBrowser";
        }

        return strtoupper($browser);
    }

    public static function ip()
    {
        $ipaddress = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }
        if ($ipaddress == '127.0.0.1' || $ipaddress == '::1') {
            return '43.166.133.3';
        }

        $ipaddress = explode(',', $ipaddress);
        return trim($ipaddress[0]);

        if (filter_var($ipaddress, FILTER_VALIDATE_IP)) {
            return $ipaddress;
        }

        return 'UNKNOWN';
    }
    public static function request($url, $method = 'GET', $data = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_USERAGENT, "hideiyh-flow@" . self::domain());
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . self::$API_KEY,
            'X-HIDEIYH-APIKEY: ' . self::$API_KEY,
            'X-HIDEIYH-DOMAIN: ' . self::domain(),
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

    public static function geolocation($ip)
    {
        $url = self::$API_BASE_URL . '/geolocation/' . $ip;
        return self::request($url);
    }
    public static function blocker($ip, $ua, $referer)
    {
        $params = [
            'ip' => $ip,
            'ua' => $ua,
            'referer' => $referer
        ];
        $url = self::$API_BASE_URL . '/blocker?' . http_build_query($params);
        return self::request($url);
    }
    public static function signature($offer_url)
    {
        $parseUrl = parse_url($offer_url);
        $domain = $parseUrl['host'] ?? '';

        if ($domain == '') {
            exit('Offer URL tidak valid untuk menggunakan fitur signature.');
        }
        $password = sha1($domain);

        return hash_hmac('sha256', $domain, $password);
    }
    public static function renderPage($url, $method)
    {
        $method = strtolower(trim((string)$method));
        if ($method === 'header_redirect') {
            header("Location: " . $url, true, 302);
            exit;
        } elseif ($method === 'meta_redirect') {
            echo "<meta http-equiv=\"refresh\" content=\"0;url={$url}\">";
            exit;
        } elseif ($method === 'script_redirect') {
            echo "<script>window.location.replace('{$url}');</script>";
            exit;
        } elseif ($method === 'local_file') {
            if (file_exists($url)) {
                include $url;
            } else {
                echo "File not found.";
            }
            exit;
        } else {
            // Default to curl redirect if somehow method doesn't match expected types
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            $response = curl_exec($ch);
            curl_close($ch);
            echo $response;
            exit;
        }
    }

    public static function matchRule($value, $ruleString)
    {
        if (empty(trim((string)$ruleString))) return true;

        $rules = array_map('trim', explode(',', strtolower((string)$ruleString)));
        $value = strtolower(trim((string)$value));

        foreach ($rules as $rule) {
            if ($rule !== '' && strpos($value, $rule) !== false) {
                return true;
            }
        }
        return false;
    }

    public static function checkAllowedParams($ruleString)
    {
        if (empty(trim((string)$ruleString))) return true;

        $params = array_map('trim', explode(',', (string)$ruleString));
        foreach ($params as $param) {
            if ($param !== '' && !isset($_GET[$param])) {
                return false;
            }
        }
        return true;
    }

    public static function logVisitor($reason, $geo = null)
    {
        $ip = self::ip();
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $referer = $_SERVER['HTTP_REFERER'] ?? 'no-referer';

        if ($geo === null) {
            $geo = self::geolocation($ip);
        }

        $data = [
            'uniqid' => CONFIG['uniqid'],
            'ip' => $ip,
            'country' => $geo['country'] ?? 'Unknown',
            'device' => self::platform('device'),
            'browser' => self::getBrowser(),
            'referer' => empty($referer) ? 'no-referer' : $referer,
            'user_agent' => empty($ua) ? 'Unknown' : $ua,
            'isp' => $geo['isp'] ?? 'Unknown',
            'reason' => $reason
        ];

        $url = self::$API_BASE_URL . '/visitor-log';
        self::request($url, 'POST', $data);
    }

    public static function syncConfig()
    {
        // Path to store our cache timestamp locally
        $cacheFile = __DIR__ . '/.hideiyh_sync_cache';
        $cacheTtl = 300; // 5 minutes cache

        // Return early if we checked recently
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTtl) {
            return;
        }

        $currentConfig = CONFIG;
        $currentHash = md5(json_encode($currentConfig));

        $url = self::$API_BASE_URL . '/load-config/' . CONFIG['uniqid'];
        $response = self::request($url);

        // Update cache timestamp regardless of success so we don't spam if API is down
        file_put_contents($cacheFile, time());

        if (isset($response['success']) && $response['success'] === true) {
            $remoteHash = $response['hash'];
            if ($currentHash !== $remoteHash && isset($response['config'])) {
                $newConfig = $response['config'];
                $file = __FILE__;
                $content = file_get_contents($file);

                // We carefully replace the array values within the template based on their keys
                foreach ($newConfig as $key => $value) {
                    // Match the specific $CONFIG['key'] = 'value'; lines and replace them
                    $pattern = "/(\\\$CONFIG\\['" . preg_quote($key, '/') . "'\\]\\s*=\\s*')[^']*(';)/";
                    $content = preg_replace($pattern, '${1}' . $value . '${2}', $content);
                }

                // Write the newly embedded config to disk
                file_put_contents($file, $content);

                // Once regenerated, we must stop the current run to let the new file take effect
                // and issue a silent redirect back to the exact same URL to reload context
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                $domainName = $_SERVER['HTTP_HOST'];
                $requestUri = $_SERVER['REQUEST_URI'];
                header("Location: " . $protocol . $domainName . $requestUri);
                exit;
            }
        }
    }

    public static function run()
    {
        // First thing, ensure we're running entirely synchronized with our remote control
        self::syncConfig();

        $ip = self::ip();
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $referer = $_SERVER['HTTP_REFERER'] ?? '';

        if (CONFIG['blocker_bots'] == '1') {
            $blockerCheck = self::blocker($ip, $ua, $referer);
            if (isset($blockerCheck['is_bot']) && $blockerCheck['is_bot'] == true) {
                self::logVisitor('bot_detected', null);
                self::renderPage(CONFIG['bot_page_url'], CONFIG['render_bot_page']);
            }
        }

        if (CONFIG['block_no_referer'] == '1' && empty($referer)) {
            self::logVisitor('no_referer', null);
            self::renderPage(CONFIG['white_page_url'], CONFIG['render_white_page']);
        }

        if (!self::checkAllowedParams(CONFIG['allowed_params'])) {
            self::logVisitor('invalid_params', null);
            self::renderPage(CONFIG['white_page_url'], CONFIG['render_white_page']);
        }

        if (!self::matchRule($referer, CONFIG['lock_referers'])) {
            self::logVisitor('invalid_referer', null);
            self::renderPage(CONFIG['white_page_url'], CONFIG['render_white_page']);
        }

        if (!self::matchRule(self::platform('device'), CONFIG['lock_device'])) {
            self::logVisitor('invalid_device', null);
            self::renderPage(CONFIG['white_page_url'], CONFIG['render_white_page']);
        }

        if (!self::matchRule(self::getBrowser(), CONFIG['lock_browser'])) {
            self::logVisitor('invalid_browser', null);
            self::renderPage(CONFIG['white_page_url'], CONFIG['render_white_page']);
        }

        $requiresGeo = !empty(trim((string)CONFIG['allowed_countries'])) || !empty(trim((string)CONFIG['lock_isp']));
        $geo = null;

        if ($requiresGeo || true) {
            // Unconditionally fetch geo right now to log accurate country and ISP if needed
            $geo = self::geolocation($ip);
        }

        if ($requiresGeo) {
            if (isset($geo['status']) && $geo['status'] === 'success') {
                if (!empty(trim((string)CONFIG['allowed_countries']))) {
                    $countryCode = $geo['countryCode'] ?? '';
                    $country = $geo['country'] ?? '';
                    if (!self::matchRule($countryCode, CONFIG['allowed_countries']) && !self::matchRule($country, CONFIG['allowed_countries'])) {
                        self::logVisitor('invalid_country', $geo);
                        self::renderPage(CONFIG['white_page_url'], CONFIG['render_white_page']);
                    }
                }

                if (!empty(trim((string)CONFIG['lock_isp']))) {
                    $isp = $geo['isp'] ?? '';
                    $org = $geo['org'] ?? '';
                    if (!self::matchRule($isp, CONFIG['lock_isp']) && !self::matchRule($org, CONFIG['lock_isp'])) {
                        self::logVisitor('invalid_isp', $geo);
                        self::renderPage(CONFIG['white_page_url'], CONFIG['render_white_page']);
                    }
                }
            } else {
                // Geo check fails therefore required rules are not satisfied
                self::logVisitor('geo_check_failed', $geo);
                self::renderPage(CONFIG['white_page_url'], CONFIG['render_white_page']);
            }
        }

        self::logVisitor('passed', $geo);

        $finalUrl = CONFIG['offer_page_url'];
        $queryString = $_SERVER['QUERY_STRING'] ?? '';
        $sig = '';

        if (CONFIG['acs'] == '1') {
            $sig = 'sig=' . self::signature($finalUrl);
        }

        if (CONFIG['allowed_params'] == '1') {
            if (!empty($queryString)) {
                $separator = (parse_url($finalUrl, PHP_URL_QUERY) == NULL) ? '?' : '&';
                $finalUrl .= $separator . $queryString;
                if (!empty($sig)) {
                    $finalUrl .= '&' . $sig;
                }
            } else {
                if (!empty($sig)) {
                    $separator = (parse_url($finalUrl, PHP_URL_QUERY) == NULL) ? '?' : '&';
                    $finalUrl .= $separator . $sig;
                }
            }
        } else {
            if (!empty($sig)) {
                $separator = (parse_url($finalUrl, PHP_URL_QUERY) == NULL) ? '?' : '&';
                $finalUrl .= $separator . $sig;
            }
        }

        self::renderPage($finalUrl, CONFIG['render_offer_page']);
    }
}


HideiyhFlow::run();
