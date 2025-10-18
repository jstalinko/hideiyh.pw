<x-filament-panels::page>

  @if(auth()->user()->gold_member)

        <x-filament::card>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                
                {{-- KOLOM KIRI: STATUS & PENGHARGAAN --}}
                <div class="flex flex-col justify-center space-y-4">
                    <div>
                        <x-filament::badge color="warning" icon="heroicon-s-star">
                            Gold Member
                        </x-filament::badge>
                    </div>
                    
                    <h2 class="text-4xl font-bold text-gray-800 dark:text-gray-200">
                        Lifetime Access Unlocked
                    </h2>

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Sebagai Gold Member, Anda memiliki akses permanen ke semua fitur premium kami. Terima kasih atas dukungan dan kepercayaan Anda.
                    </p>

                    {{-- Tombol Aksi untuk Gold Member --}}
                    <div class="pt-4">
                        <x-filament::button
                            {{-- Arahkan ke halaman manajemen domain atau halaman relevan lainnya --}}
                            {{-- href="#" --}}
                            tag="a"
                            icon="heroicon-m-globe-alt">
                            Manage My Domains
                        </x-filament::button>
                    </div>
                </div>

                {{-- KOLOM KANAN: DETAIL KUOTA & FITUR --}}
                <div class="space-y-4 bg-gray-50 dark:bg-gray-800/50 p-6 rounded-lg">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                        Detail Keanggotaan
                    </h3>
                
                    <dl class="text-sm">
                        {{-- Status --}}
                        <div class="flex justify-between items-center py-2 border-b dark:border-gray-700">
                            <dt class="text-gray-500 dark:text-gray-400">Status Langganan</dt>
                            <dd class="flex items-center gap-x-2 font-semibold text-green-600 dark:text-green-400">
                                <x-heroicon-c-star class="w-5 h-5" />
                                <span>Aktif Selamanya</span>
                            </dd>
                        </div>

                        {{-- Kuota Visitor --}}
                        <div class="flex justify-between items-center py-2 border-b dark:border-gray-700">
                            <dt class="text-gray-500 dark:text-gray-400">Kuota Visitor / Hari</dt>
                            <dd class="flex items-center gap-x-2 font-semibold">
                                <x-heroicon-c-star class="w-5 h-5" />
                                <span>Unlimited</span>
                            </dd>
                        </div>
                        
                        {{-- Kuota Domain --}}
                        <div class="flex justify-between items-center py-2 border-b dark:border-gray-700">
                            <dt class="text-gray-500 dark:text-gray-400">Kuota Domain</dt>
                            {{-- Pastikan ada kolom 'domain_quota' di tabel users --}}
                            <dd class="font-bold text-lg">{{ auth()->user()->domain_quota ?? 'N/A' }}</dd>
                        </div>

                        {{-- Fitur --}}
                        <div class="pt-2">
                            <dt class="text-gray-500 dark:text-gray-400 font-semibold mb-2">Semua Layanan Aktif:</dt>
                            <dd>
                                <ul class="space-y-1">
                                    <li class="flex items-center gap-x-2"><x-heroicon-o-check-circle class="w-4 h-4 text-primary-500" /> <span>ACS Cloaking Script</span></li>
                                    <li class="flex items-center gap-x-2"><x-heroicon-o-check-circle class="w-4 h-4 text-primary-500" /> <span>API Geolocation</span></li>
                                    <li class="flex items-center gap-x-2"><x-heroicon-o-check-circle class="w-4 h-4 text-primary-500" /> <span>API Blocker</span></li>
                                </ul>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </x-filament::card>
        @elseif ($subscription)
        {{-- TAMPILAN JIKA ADA LANGGANAN AKTIF (Tidak ada perubahan di sini) --}}
<x-filament::card>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
        
        {{-- =================================== --}}
        {{--        KOLOM KIRI: DETAIL PAKET       --}}
        {{-- =================================== --}}
        <div class="space-y-4">
            <h2 class="text-md font-bold text-gray-800 dark:text-gray-200">
                Paket Anda Saat Ini
            </h2>
            
            {{-- Nama Paket --}}
            <h1 class="text-xl font-bold">
                {{ $subscription->package->name }}
            </h1>

            {{-- ... sisa detail paket tidak berubah ... --}}
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ $subscription->package->description }}
            </p>

            <div class="pt-2">
                <h3 class="font-semibold mb-2">Fitur Termasuk:</h3>
                <ul class="space-y-2 text-sm">
                    @if($subscription->package->feature_acs_cloaking_script)
                        <li class="flex items-center gap-x-2">
                            <x-heroicon-o-check-circle class="w-5 h-5 text-green-500" />
                            <span>ACS Cloaking Script</span>
                        </li>
                    @endif
                    @if($subscription->package->feature_api_geolocation)
                        <li class="flex items-center gap-x-2">
                            <x-heroicon-o-check-circle class="w-5 h-5 text-green-500" />
                            <span>API Geolocation Access</span>
                        </li>
                    @endif
                    @if($subscription->package->feature_api_blocker)
                        <li class="flex items-center gap-x-2">
                            <x-heroicon-o-check-circle class="w-5 h-5 text-green-500" />
                            <span>API Blocker Access</span>
                        </li>
                    @endif
                    <li class="flex items-center gap-x-2">
                            <x-heroicon-o-check-circle class="w-5 h-5 text-green-500" />
                            <span>{{ number_format($subscription->package->visitor_quota_perday) }} Traffic Limit/Day/Domain</span>
                        </li>
                    <li class="flex items-center gap-x-2">
                          <x-heroicon-o-check-circle class="w-5 h-5 text-green-500" />
                            <span>{{ number_format($subscription->package->domain_quota) }} Domain</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- ===================================== --}}
        {{--      KOLOM KANAN: DETAIL LANGGANAN      --}}
        {{-- ===================================== --}}
        <div class="space-y-4 border p-6 rounded-lg flex flex-col">
            <div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                    Detail Langganan
                </h3>
            
                <dl class="text-sm mt-4">
                    {{-- Status --}}
                    <div class="flex justify-between py-2 border-b dark:border-gray-700">
                        <dt class="text-gray-500 dark:text-gray-400">Status</dt>
                        <dd>
                            <x-filament::badge
                                color="{{ match($subscription->status) {'active' => 'success', 'past_due' => 'warning', 'expired' => 'danger', 'canceled' => 'gray', default => 'info'} }}"
                                class="capitalize"
                            >
                                {{ str_replace('_', ' ', $subscription->status) }}
                            </x-filament::badge>
                        </dd>
                    </div>

                    {{-- Kode Langganan --}}
                    <div class="flex justify-between py-2 border-b dark:border-gray-700">
                        <dt class="text-gray-500 dark:text-gray-400">Kode Langganan</dt>
                        <dd class="font-mono text-xs font-semibold">{{ $subscription->subscription_code }}</dd>
                    </div>

                    {{-- Tanggal Mulai --}}
                    <div class="flex justify-between py-2 border-b dark:border-gray-700">
                        <dt class="text-gray-500 dark:text-gray-400">Aktif Sejak</dt>
                        {{-- Memformat tanggal agar lebih rapi --}}
                        <dd class="font-semibold">{{ $subscription->start_at }}</dd>
                    </div>

                    {{-- Tanggal Berakhir --}}
                    <div class="flex justify-between py-2 border-b dark:border-gray-700">
                        <dt class="text-gray-500 dark:text-gray-400">Berakhir Pada</dt>
                        <dd class="font-semibold">{{ $subscription->end_at }}</dd>
                    </div>
                    
                    {{-- ... sisa detail kuota dan harga ... --}}
                    <div class="flex justify-between py-2 border-b dark:border-gray-700">
                        <dt class="text-gray-500 dark:text-gray-400">Kuota Domain</dt>
                        <dd class="font-semibold">{{ $subscription->package->domain_quota }}</dd>
                    </div>
                    
                    <div class="flex justify-between py-2 border-b dark:border-gray-700">
                        <dt class="text-gray-500 dark:text-gray-400">Kuota Visitor / Hari</dt>
                        <dd class="font-semibold">
                            @if($subscription->package->visitor_quota_perday == -1)
                                Unlimited
                            @else
                                {{ number_format($subscription->package->visitor_quota_perday, 0, ',', '.') }}
                            @endif
                        </dd>
                    </div>

                    <div class="flex justify-between pt-2">
                        <dt class="text-gray-500 dark:text-gray-400">Harga</dt>
                        <dd class="font-bold text-lg">Rp {{ number_format($subscription->price, 0, ',', '.') }}</dd>
                    </div>
                </dl>
            </div>

            {{-- ================================= --}}
            {{--         BAGIAN TOMBOL BARU        --}}
            {{-- ================================= --}}
            <div class="flex-grow flex items-end">
                <div class="w-full flex items-center gap-4 pt-4 mt-4 border-t dark:border-gray-700">
                    <x-filament::button
                        wire:click="goToUpgrade"
                        icon="heroicon-m-arrow-up-circle"
                        class="flex-1">
                        Upgrade Plan
                    </x-filament::button>
                    
                    {{-- Tombol ini akan merender Action yang kita buat di PHP --}}
                    <div class="flex-1">
                        {{ $this->cancelSubscriptionAction() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-filament::card>

    @else
   
    
        {{-- TAMPILAN JIKA TIDAK ADA LANGGANAN AKTIF --}}
        <x-filament::card class="text-center">
            <div class="flex flex-col items-center justify-center space-y-4 py-8">
                <div class="p-4 bg-primary-500/10 rounded-full">
                    <x-heroicon-o-x-circle class="w-12 h-12 text-primary-500" />
                </div>
                <h2 class="text-2xl font-bold tracking-tight">
                    Nothing subscriptions active
                </h2>
                <p class="max-w-md text-gray-500 dark:text-gray-400">
                    Anda saat ini tidak memiliki paket langganan aktif. Silakan pilih paket atau masukkan kode langganan untuk memulai.
                </p>

                {{-- Grup Tombol --}}
                <div class="flex items-center gap-4 pt-4">
                    
                    {{-- PERUBAHAN DI SINI: Panggil metode action yang baru --}}
                    {{ $this->inputCodeAction() }}

                    <x-filament::button
                        wire:click="goToPricing"
                        color="gray"
                        icon="heroicon-m-shopping-cart">
                        Subscription Plan
                    </x-filament::button>
                </div>
            </div>
        </x-filament::card>
    @endif
    

</x-filament-panels::page>