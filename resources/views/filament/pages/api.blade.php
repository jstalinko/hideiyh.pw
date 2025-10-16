<x-filament-panels::page>

  {{-- =================================== --}}
    {{--        BAGIAN API KEY PENGGUNA        --}}
    {{-- =================================== --}}
    <x-filament::section>
        <x-slot name="heading">
            Your API Key
        </x-slot>

        <div
            x-data="{
                apiKey: '{{ auth()->user()->apikey }}',
                copyMessage: 'Copy'
            }"
            class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg"
        >
            {{-- Tampilkan API Key --}}
            <code class="font-mono text-sm text-primary-600 dark:text-primary-400 break-all mr-4">
                {{ auth()->user()->apikey }}
            </code>

            {{-- Grup Tombol --}}
            <div class="flex-shrink-0 flex items-center gap-x-2">
                {{-- Tombol untuk menyalin API Key --}}
                <x-filament::button
                    color="gray"
                    icon="heroicon-o-clipboard-document"
                    x-on:click="navigator.clipboard.writeText(apiKey); copyMessage = 'Copied!'; setTimeout(() => copyMessage = 'Copy', 2000)"
                >
                    <span x-text="copyMessage"></span>
                </x-filament::button>

                {{-- Tombol "Revoke" yang merender Aksi dari PHP --}}
                {{ $this->revokeApiKeyAction() }}
            </div>
        </div>
    </x-filament::section>

    {{-- =================================== --}}
    {{--        BAGIAN INTEGRATION BARU        --}}
    {{-- =================================== --}}
    <x-filament::section>
        <x-slot name="heading">
            Integration
        </x-slot>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
            Use the following code example to integrate our API into your application.
        </p>
        
        {{-- Tombol "Example PHP Code" yang merender Aksi dari PHP --}}
        {{ $this->phpExampleAction() }}
       

    </x-filament::section>

    {{-- =================================== --}}
    {{--      BAGIAN DOKUMENTASI API         --}}
    {{-- =================================== --}}
    <div class="space-y-8">
        <h1 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white">API Documentation</h1>

        {{-- --------------------------------- --}}
        {{--        Endpoint 1: Blocker        --}}
        {{-- --------------------------------- --}}
        <x-filament::section>
            <x-slot name="heading">
                Blocker API Endpoint
            </x-slot>

            <div class="space-y-4">
                {{-- URL & Method --}}
                <div class="flex items-center gap-x-4">
                    <x-filament::badge color="success">GET</x-filament::badge>
                    <code class="font-mono text-sm">https://hideiyh.pw/api/blocker</code>
                </div>

                {{-- Authentication --}}
                <div>
                    <h3 class="font-semibold">Authentication</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Sertakan API Key Anda pada header request sebagai berikut:
                        <br>
                        <pre class="font-mono text-xs">X-HIDEIYH-APIKEY: {Your_API_Key}</pre>
                    </p>
                    <br>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Sertakan nama domain web yang anda gunakan sebagai berikut:
                        <br>
                        <pre class="font-mono text-xs">X-HIDEIYH-DOMAIN: {Your_Web_Domain}</pre>
                    </p>
                </div>

                {{-- Parameters --}}
                <div>
                    <h3 class="font-semibold">Query Parameters</h3>
                    <ul class="list-disc list-inside text-sm text-gray-500 dark:text-gray-400 space-y-1 mt-1">
                        <li><code class="font-mono text-xs">ip</code> (wajib): Alamat IP yang ingin dicek. Jika kosong, akan menggunakan IP peminta.</li>
                        <li><code class="font-mono text-xs">ua</code> (wajib): User-Agent yang ingin dicek (pastikan di-URL Encode). Jika kosong, akan menggunakan User-Agent peminta.</li>
                        <li><code class="font-mono text-xs">referrer</code> (opsional): Domain referrer. Jika kosong, akan menggunakan header 'referer' peminta.</li>
                    </ul>
                </div>

                {{-- Example Responses --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                    <div>
                        <h3 class="font-semibold">Example Success Response</h3>
                        <pre class="mt-1 p-3 bg-gray-100 dark:bg-gray-800 rounded-lg overflow-x-auto"><code class="language-json text-xs">{{ json_encode(json_decode('{
    "status": "success",
    "is_bot": true,
    "reason": "Blocked by agent detected bot",
    "ip": "8.8.8.8",
    "user_agent": "googlebot",
    "request_time": "2025-10-16T18:11:19+00:00"
}'), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                    </div>
                    <div>
                        <h3 class="font-semibold">Example Error Response</h3>
                        <pre class="mt-1 p-3 bg-gray-100 dark:bg-gray-800 rounded-lg overflow-x-auto"><code class="language-json text-xs">{{ json_encode(json_decode('{
    "status": "error",
    "message": "Invalid API Key"
}'), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                    </div>
                </div>
            </div>
        </x-filament::section>

        {{-- ----------------------------------- --}}
        {{--        Endpoint 2: Geolocation      --}}
        {{-- ----------------------------------- --}}
        <x-filament::section>
            <x-slot name="heading">
                Geolocation API Endpoint
            </x-slot>

            <div class="space-y-4">
                {{-- URL & Method --}}
                <div class="flex items-center gap-x-4">
                    <x-filament::badge color="success">GET</x-filament::badge>
                    <code class="font-mono text-sm">https://hideiyh.pw/api/geolocation/{ip}</code>
                </div>

                 {{-- Authentication --}}
                <div>
                    <h3 class="font-semibold">Authentication</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Sertakan API Key Anda pada header request sebagai berikut:
                        <br>
                        <pre class="font-mono text-xs">X-HIDEIYH-APIKEY: {Your_API_Key}</pre>
                    </p>
                    <br>
                     <p class="text-sm text-gray-500 dark:text-gray-400">
                        Sertakan nama domain web yang anda gunakan sebagai berikut:
                        <br>
    
                        <pre class="font-mono text-xs">X-HIDEIYH-DOMAIN: {Your_Web_Domain}</pre>
                    </p>
                </div>

                {{-- Parameters --}}
                <div>
                    <h3 class="font-semibold">URL Parameters</h3>
                    <ul class="list-disc list-inside text-sm text-gray-500 dark:text-gray-400 space-y-1 mt-1">
                        <li><code class="font-mono text-xs">{ip}</code> (wajib): Alamat IP yang ingin dicari data lokasinya.</li>
                    </ul>
                </div>

                {{-- Example Responses --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                    <div>
                        <h3 class="font-semibold">Example Success Response</h3>
                        <pre class="mt-1 p-3 bg-gray-100 dark:bg-gray-800 rounded-lg overflow-x-auto"><code class="language-json text-xs">{{ json_encode(json_decode('{
    "status":"success",
    "country":"United States",
    "countryCode":"US",
    "region":"VA",
    "regionName":"Virginia",
    "city":"Ashburn",
    "zip":"20149",
    "lat":39.03,
    "lon":-77.5,
    "timezone":"America/New_York",
    "isp":"Google LLC",
    "org":"Google Public DNS",
    "as":"AS15169 Google LLC",
    "query":"8.8.8.8"
}'), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                    </div>
                    <div>
                        <h3 class="font-semibold">Example Error Response</h3>
                        <pre class="mt-1 p-3 bg-gray-100 dark:bg-gray-800 rounded-lg overflow-x-auto"><code class="language-json text-xs">{{ json_encode(json_decode('{
    "status": "error",
    "message": "IP address not found"
}'), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                    </div>
                </div>
            </div>
        </x-filament::section>

    </div>

</x-filament-panels::page>