<x-filament-widgets::widget>
    <x-filament::section>
        {{-- Widget content --}}
        <h2 class="text-lg font-bold mb-4">Akses Saya & Modul</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($modules as $module)
                <div class="p-4 border rounded-lg shadow-sm flex flex-col items-center text-center">
                    
                    <x-filament::icon
                        icon="{{ $module->icon ?? 'heroicon-o-cube' }}"
                        class="h-10 w-10 text-primary-500 mb-2"
                    />
                    
                    <h3 class="font-semibold">{{ $module->name }}</h3>
                    <p class="text-sm text-gray-500 mb-4">{{ $module->description }}</p>

                    <!-- Logic Checks -->
                    @if($user->hasRole($module->code))
                        <x-filament::button tag="a" href="{{ url($module->url_path) }}" color="success">
                            Buka Sistem
                        </x-filament::button>

                    @elseif($user->moduleRequests()->where('module_id', $module->id)->where('status', 'pending')->exists())
                        <x-filament::button color="warning" disabled>
                            Sedang Diproses
                        </x-filament::button>

                    @else
                        <!-- wire:click triggers the PHP function in the widget class -->
                        <x-filament::button wire:click="requestAccess({{ $module->id }})" color="primary">
                            Mohon Akses
                        </x-filament::button>
                    @endif

                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
