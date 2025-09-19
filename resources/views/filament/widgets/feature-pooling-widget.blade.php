<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-4">
            <div class="text-xl font-bold">
                Next update feature pooling
            </div>
            
            <div class="space-y-2">
                @foreach($this->features as $feature)
                    <div class="flex items-center justify-between p-4 bg-dark border-1 rounded-lg shadow">
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-300">{{ $feature->title }}</h3>
                            <p class="text-sm text-gray-500">{{ $feature->description }}</p>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <span class="text-lg font-semibold">
                                {{ $feature->votes_count }} votes &nbsp;
                            </span>
                            
                            @if($feature->status != 'done')
                            <button
                                wire:click="vote({{ $feature->id }})"
                                @class([
                                    'px-4 py-2 text-sm font-medium rounded-md',
                                    'bg-primary-600 text-white hover:bg-primary-500' => ! $feature->votes->contains('user_id', auth()->id()),
                                    'bg-gray-200 text-gray-700 hover:bg-gray-300' => $feature->votes->contains('user_id', auth()->id()),
                                ])
                            >
                                {{ $feature->votes->contains('user_id', auth()->id()) ? 'Remove Vote' : 'Vote' }}
                            </button>
                            @endif

                            @if($feature->status == 'active')
                            <span class="px-2 py-1 text-xs font-semibold text-green-600 bg-green-100 rounded-full">
                                Berlangsung
                            </span>
                            @elseif($feature->status == 'done')
                            <span class="px-2 py-1 text-xs font-semibold text-green-600 bg-green-100 rounded-full">
                                Di terapkan
                            </span>
                            @else
                            <span class="px-2 py-1 text-xs font-semibold text-red-600 bg-red-100 rounded-full">
                                {{ strtoupper($feature->status) }}
                            </span>
                            @endif

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>