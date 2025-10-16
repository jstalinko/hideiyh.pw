{{-- resources/views/filament/modals/php-code-example.blade.php --}}
<div x-data="{ activeTab: 'php' }">
    <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex space-x-4" aria-label="Tabs">
            {{-- Tombol Tab PHP --}}
            <button
                type="button" {{-- <-- FIX DITAMBAHKAN --}}
                @click="activeTab = 'php'"
                :class="{
                    'border-primary-500 text-primary-600 dark:text-primary-400': activeTab === 'php',
                    'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'php'
                }"
                class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm"
            >
                PHP
            </button>

            {{-- Tombol Tab Node.js --}}
            <button
                type="button" {{-- <-- FIX DITAMBAHKAN --}}
                @click="activeTab = 'nodejs'"
                :class="{
                    'border-primary-500 text-primary-600 dark:text-primary-400': activeTab === 'nodejs',
                    'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'nodejs'
                }"
                class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm"
            >
                Node.js
            </button>

            {{-- Tombol Tab Python --}}
            <button
                type="button" {{-- <-- FIX DITAMBAHKAN --}}
                @click="activeTab = 'python'"
                :class="{
                    'border-primary-500 text-primary-600 dark:text-primary-400': activeTab === 'python',
                    'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'python'
                }"
                class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm"
            >
                Python
            </button>
        </nav>
    </div>

    {{-- ======================= --}}
    {{--    KONTEN TAB PHP       --}}
    {{-- ======================= --}}
    <div x-show="activeTab === 'php'" x-cloak>
        <div class="relative bg-gray-900 dark:bg-gray-800 rounded-lg p-4 group">
            <div x-data="{ copyMessage: 'Copy' }" class="absolute top-2 right-2">
                <x-filament::button
                    color="gray"
                    size="xs"
                    x-on:click="navigator.clipboard.writeText($refs.phpCode.textContent); copyMessage = 'Copied!'; setTimeout(() => copyMessage = 'Copy', 2000)"
                >
                    <span x-text="copyMessage"></span>
                </x-filament::button>
            </div>
            <pre class="overflow-x-auto"><code x-ref="phpCode" class="language-php text-xs text-white">&lt;?php
/**--------------------------------------
 * HIDEIYH.PW 2025 - PHP Example
 */

function getDomain(): string {
    $host = $_SERVER['HTTP_HOST'] ?? '';
    return preg_replace('/^www\./i', '', $host);
}

function _hideiyh_api_request(string $apiKey, string $apiUrl): array {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPHEADER => [
            'X-HIDEIYH-APIKEY: ' . $apiKey,
            'X-HIDEIYH-DOMAIN: ' . getDomain()
        ]
    ]);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        return ['status' => 'error', 'message' => 'cURL Error: ' . $error_msg];
    }
    curl_close($ch);
    $data = json_decode($response, true);
    return json_last_error() === JSON_ERROR_NONE ? $data : ['status' => 'error', 'message' => 'Invalid JSON response.'];
}

function hideiyh_blocker(string $apiKey, string $ip, string $ua): array {
    $apiUrl = "https://hideiyh.pw/api/blocker?ip={$ip}&ua=" . urlencode($ua);
    return _hideiyh_api_request($apiKey, $apiUrl);
}

function hideiyh_geo(string $apiKey, string $ip): array {
    $apiUrl = "https://hideiyh.pw/api/geolocation/{$ip}";
    return _hideiyh_api_request($apiKey, $apiUrl);
}

// Data:
$apikey = "{{ auth()->user()->apikey }}"; // INI ADALAH APIKEY KAMU. 
$ip = $_SERVER['REMOTE_ADDR'];
$ua = $_SERVER['HTTP_USER_AGENT'];

// Usage:
$geo = hideiyh_geo($apikey, $ip);
$blocker = hideiyh_blocker($apikey, $ip, $ua);

print_r($blocker);
?&gt;{{--
--}}</code></pre>
        </div>
    </div>

    {{-- ======================= --}}
    {{--    KONTEN TAB NODE.JS   --}}
    {{-- ======================= --}}
    <div x-show="activeTab === 'nodejs'" x-cloak>
        <p class="text-xs text-gray-500 mb-2">Pastikan Anda telah menginstal axios: <code class="text-xs">npm install axios</code></p>
        <div class="relative bg-gray-900 dark:bg-gray-800 rounded-lg p-4 group">
            <div x-data="{ copyMessage: 'Copy' }" class="absolute top-2 right-2">
                <x-filament::button
                    color="gray"
                    size="xs"
                    x-on:click="navigator.clipboard.writeText($refs.nodeCode.textContent); copyMessage = 'Copied!'; setTimeout(() => copyMessage = 'Copy', 2000)"
                >
                    <span x-text="copyMessage"></span>
                </x-filament::button>
            </div>
            <pre class="overflow-x-auto"><code x-ref="nodeCode" class="language-js text-xs text-white">{{--
--}}/**
 * HIDEIYH.PW 2025 - Node.js Example
 */
const axios = require('axios');

function getDomain() {
    return 'your-domain.com'; // Set domain Anda secara manual
}

async function _hideiyhApiRequest(apiKey, apiUrl) {
    try {
        const response = await axios.get(apiUrl, {
            headers: {
                'X-HIDEIYH-APIKEY': apiKey,
                'X-HIDEIYH-DOMAIN': getDomain(),
            },
            timeout: 10000, // 10 detik
        });
        return response.data;
    } catch (error) {
        return { status: 'error', message: error.message };
    }
}

async function hideiyhBlocker(apiKey, ip, ua) {
    const apiUrl = `https://hideiyh.pw/api/blocker?ip=${ip}&ua=${encodeURIComponent(ua)}`;
    return _hideiyhApiRequest(apiKey, apiUrl);
}

async function hideiyhGeo(apiKey, ip) {
    const apiUrl = `https://hideiyh.pw/api/geolocation/${ip}`;
    return _hideiyhApiRequest(apiKey, apiUrl);
}

// --- Contoh Penggunaan ---
async function main() {
    const apikey = "{{ auth()->user()->apikey }}"; // INI ADALAH APIKEY KAMU.
    const ip = "8.8.8.8";
    const ua = "Mozilla/5.0";

    const blocker = await hideiyhBlocker(apikey, ip, ua);
    console.log("== BLOCKER RESULT ==", blocker);
}

main();{{--
--}}</code></pre>
        </div>
    </div>

    {{-- ======================= --}}
    {{--    KONTEN TAB PYTHON    --}}
    {{-- ======================= --}}
    <div x-show="activeTab === 'python'" x-cloak>
        <p class="text-xs text-gray-500 mb-2">Pastikan Anda telah menginstal requests: <code class="text-xs">pip install requests</code></p>
        <div class="relative bg-gray-900 dark:bg-gray-800 rounded-lg p-4 group">
            <div x-data="{ copyMessage: 'Copy' }" class="absolute top-2 right-2">
                <x-filament::button
                    color="gray"
                    size="xs"
                    x-on:click="navigator.clipboard.writeText($refs.pythonCode.textContent); copyMessage = 'Copied!'; setTimeout(() => copyMessage = 'Copy', 2000)"
                >
                    <span x-text="copyMessage"></span>
                </x-filament::button>
            </div>
            <pre class="overflow-x-auto"><code x-ref="pythonCode" class="language-python text-xs text-white">{{--
--}}"""
HIDEIYH.PW 2025 - Python Example
"""
import requests
from urllib.parse import quote_plus

def get_domain() -> str:
    return 'your-domain.com' # Set domain Anda secara manual

def _hideiyh_api_request(api_key: str, api_url: str) -> dict:
    headers = {
        'X-HIDEIYH-APIKEY': api_key,
        'X-HIDEIYH-DOMAIN': get_domain()
    }
    try:
        response = requests.get(api_url, headers=headers, timeout=10)
        response.raise_for_status()
        return response.json()
    except requests.exceptions.RequestException as e:
        return {'status': 'error', 'message': f'Request Error: {e}'}

def hideiyh_blocker(api_key: str, ip: str, ua: str) -> dict:
    api_url = f"https://hideiyh.pw/api/blocker?ip={ip}&ua={quote_plus(ua)}"
    return _hideiyh_api_request(api_key, api_url)

def hideiyh_geo(api_key: str, ip: str) -> dict:
    api_url = f"https://hideiyh.pw/api/geolocation/{ip}"
    return _hideiyh_api_request(api_key, api_url)

# --- Contoh Penggunaan ---
if __name__ == "__main__":
    apikey = "{{ auth()->user()->apikey }}" # INI ADALAH APIKEY KAMU.
    ip = "8.8.8.8"
    ua = "Mozilla/5.0"

    blocker = hideiyh_blocker(apikey, ip, ua)
    print("== BLOCKER RESULT ==")
    print(blocker)
{{--
--}}</code></pre>
        </div>
    </div>
</div>