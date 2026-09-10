<x-filament-panels::page>
    {{-- Page content --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="md:col-span-2">
            @livewire(\App\Filament\Widgets\ModuleRequestWidget::class)
        </div>

        <div class="md:col-span-1">
            <x-filament::section>
                <h3 class="font-bold">Cara Memohon</h3>
                <p class="text-sm text-gray-500">
                    Sila klik butang mohon pada modul yang anda perlukan. 
                    Kelulusan akan diberikan oleh Pentadbir Utama.
                </p>
            </x-filament::section>
        </div>

    </div>
</x-filament-panels::page>
