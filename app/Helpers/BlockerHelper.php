<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BlockerHelper
{
    private static int $cacheDurationInMinutes = 1440; // 24 hours

    private static function loadBotList(string $filename): array
    {
        $cacheKey = 'bot_list_' . str_replace('.txt', '', $filename);
        return Cache::remember($cacheKey, self::$cacheDurationInMinutes, function () use ($filename) {
            $path = database_path('bots/' . $filename);
            if (!File::exists($path)) {
                return [];
            }
            $content = File::get($path);
            return array_filter(array_map('trim', explode("\n", $content)));
        });
    }

    /**
     * Cek IP: Coba regex dulu, jika error, fallback ke wildcard.
     *
     * @param string $ip
     * @return bool
     */
    public static function isIpBot(string $ip): bool
    {
        $ipList = self::loadBotList('ip.txt');
        foreach ($ipList as $pattern) {
            // Coba preg_match dulu, @ menekan warning jika pattern tidak valid
            $regexMatch = @preg_match('#' . $pattern . '#', $ip);

            if ($regexMatch === 1) {
                // Regex valid dan cocok
                return true;
            } elseif ($regexMatch === false) {
                // Regex TIDAK VALID, fallback ke fnmatch
                Log::warning('HelperBlocker: Invalid regex in ip.txt, falling back to wildcard.', ['pattern' => $pattern]);
                if (fnmatch($pattern, $ip)) {
                    return true;
                }
            }
            // Jika $regexMatch === 0, berarti regex valid tapi tidak cocok, lanjut ke pattern berikutnya
        }
        return false;
    }

    /**
     * Cek apakah User-Agent ada di dalam daftar blokir (string-matching sederhana).
     *
     * @param string $userAgent
     * @return bool
     */
    public static function isAgentBot(string $userAgent): bool
    {
        $agentList = self::loadBotList('agent.txt');
        $lowerUserAgent = strtolower($userAgent);

        foreach ($agentList as $agent) {
            if (str_contains($lowerUserAgent, strtolower($agent))) {
                return true;
            }
        }
        return false;
    }

    /**
     * Cek Crawler: Coba regex dulu, jika error, fallback ke wildcard.
     *
     * @param string $userAgent
     * @return bool
     */
    public static function isCrawlerBot(string $userAgent): bool
    {
        $crawlerList = self::loadBotList('crawlers.txt');
        foreach ($crawlerList as $pattern) {
            // Coba preg_match dulu, 'i' untuk case-insensitive
            $regexMatch = @preg_match('#' . $pattern . '#i', $userAgent);

            if ($regexMatch === 1) {
                // Regex valid dan cocok
                return true;
            } elseif ($regexMatch === false) {
                // Regex TIDAK VALID, fallback ke fnmatch
                //Log::warning('HelperBlocker: Invalid regex in crawlers.txt, falling back to wildcard.', ['pattern' => $pattern]);
                if (fnmatch($pattern, $userAgent, FNM_CASEFOLD)) {
                    return true;
                }
            }
        }
        return false;
    }

    // ... sisa metode (isHostBot, isBadword, isIspBot, dll.) tidak perlu diubah ...
    
    public static function isHostBot(?string $referrer): bool
    {
        if (empty($referrer)) return false;
        $host = parse_url($referrer, PHP_URL_HOST);
        if (empty($host)) return false;
        $hostList = array_merge(self::loadBotList('host.txt'), self::loadBotList('domain.txt'));
        return in_array($host, $hostList);
    }
    
    public static function isBadword(?string $stringToCheck): bool
    {
        if (empty($stringToCheck)) return false;
        $badwordList = self::loadBotList('badword.txt');
        $lowerString = strtolower($stringToCheck);
        foreach ($badwordList as $word) {
            if (str_contains($lowerString, strtolower($word))) return true;
        }
        return false;
    }
    
    public static function isIspBot(string $ip): bool
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) return false;
        try {
            $ispList = self::loadBotList('isp.txt');
            $geoData = Cache::remember('geolocation_'.$ip, now()->addMonth(), function () use ($ip) {
                $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}?fields=isp");
                return $response->failed() ? null : $response->json();
            });
            if (isset($geoData['isp'])) {
                $lowerIsp = strtolower($geoData['isp']);
                foreach ($ispList as $blockedIsp) {
                    if (str_contains($lowerIsp, strtolower($blockedIsp))) return true;
                }
            }
        } catch (\Throwable $e) {
            Log::warning('HelperBlocker: ISP check failed. ' . $e->getMessage());
        }
        return false;
    }

   public static function getBotChecks(string $ip, string $userAgent, ?string $referrer, bool $checkIsp = false): array
    {
        // ❌ HAPUS: Baris-baris ini yang menyebabkan masalah
        // $request = request();
        // $ip = $request->ip();
        // $userAgent = $request->userAgent() ?? '';
        // $referrer = $request->header('referer');

        $results = [
            'ip' => self::isIpBot($ip),
            'agent' => self::isAgentBot($userAgent),
            'crawler' => self::isCrawlerBot($userAgent),
            'host' => self::isHostBot($referrer),
            'badword_agent' => self::isBadword($userAgent),
            'badword_referrer' => self::isBadword($referrer),
            'isp' => false, // default
        ];
        
        if ($checkIsp) {
            $results['isp'] = self::isIspBot($ip);
        }

        return $results;
    }

    /**
     * Metode simpel untuk mengecek apakah request terdeteksi sebagai bot.
     *
     * @param bool $checkIsp
     * @return bool
     */
    // ✅ PERUBAHAN: Tambahkan parameter agar bisa diteruskan ke getBotChecks
    public static function isBot(string $ip, string $userAgent, ?string $referrer, bool $checkIsp = false): bool
    {
        // Teruskan argumen ke getBotChecks
        $checks = self::getBotChecks($ip, $userAgent, $referrer, $checkIsp);
        return in_array(true, $checks, true);
    }
}