<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold tracking-tight">What's New?</h2>
            </div>

            <div class="space-y-4 max-h-[36rem] p-3 overflow-y-auto">
                @foreach($updates as $update)
                    <div class="relative flex gap-4">
                        <div class="flex h-6 items-center">
                            <div class="relative z-10 w-1.5 h-1.5 rounded-full ring-2 ring-white 
                                {{ match($update['type']) {
                                    'feature' => 'bg-success-500',
                                    'improvement' => 'bg-info-500',
                                    'fix' => 'bg-warning-500',
                                    default => 'bg-gray-500'
                                } }}">
                            </div>
                            @if(!$loop->last)
                                <div class="absolute top-6 left-[0.3125rem] bottom-0 w-px bg-gray-200"></div>
                            @endif
                        </div>
                        
                        <div class="flex-1 pt-0.5">
                            <div class="flex items-center gap-x-2">
                                <h3 class="font-medium">{{ $update['title'] }}</h3>
                                <span class="rounded-full px-2 py-0.5 text-xs font-medium
                                    {{ match($update['type']) {
                                        'feature' => 'bg-success-50 text-success-700',
                                        'improvement' => 'bg-info-50 text-info-700',
                                        'fix' => 'bg-warning-50 text-warning-700',
                                        default => 'bg-gray-50 text-gray-700'
                                    } }}">
                                    {{ ucfirst($update['type']) }}
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">{{ $update['description'] }}</p>
                            <time class="mt-1 text-xs text-gray-400">
                                {{ \Carbon\Carbon::parse($update['created_at'])->diffForHumans() }}
                            </time>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>