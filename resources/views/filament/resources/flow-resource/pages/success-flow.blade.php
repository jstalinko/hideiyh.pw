<x-filament-panels::page>
    <div class="fi-ta-empty-state px-6 py-12">
        <div class="fi-ta-empty-state-content mx-auto grid max-w-lg justify-items-center text-center">

            <div class="fi-ta-empty-state-icon-ctn mb-4 rounded-full bg-success-100 p-3 dark:bg-success-500/20">
                <x-heroicon-o-check-circle
                    class="fi-ta-empty-state-icon h-12 w-12 text-success-500 dark:text-success-400" />
            </div>

            <h4 class="fi-ta-empty-state-heading text-2xl font-semibold leading-6 text-gray-950 dark:text-white mt-4">
                Flow Created Successfully
            </h4>

            <p class="fi-ta-empty-state-description text-base text-gray-500 dark:text-gray-400 mt-4 mb-8">
                Your flow <strong>{{ $this->record->name }}</strong> has been successfully created. You can now download
                it or go back to the flow list.
            </p>

            <div class="flex gap-4 p-4 mt-6">
                <!-- If you want the actions in the center instead of header -->
                <x-filament::button color="success" icon="heroicon-o-arrow-down-tray" tag="a"
                    href="/dl-flow/{{ $this->record->uniqid }}">
                    Download
                </x-filament::button>

                <x-filament::button color="gray" icon="heroicon-o-list-bullet" tag="a"
                    href="{{ \App\Filament\Resources\FlowResource::getUrl('index') }}">
                    Flow List
                </x-filament::button>
            </div>
        </div>
    </div>
</x-filament-panels::page>
