<x-filament-panels::page>
    {{-- Page content --}}
    <x-filament::section>
        
        <x-slot name="heading">
            {{ __('my_access.access_heading') }}
        </x-slot>
        <x-slot name="description">
            {{ __('my_access.access_desc') }}
        </x-slot>

        {{ $this->form }}

        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg text-sm text-gray-600 dark:text-gray-200 border-gray-200">
            <!-- <span class="font-bold text-gray-900 dark:text-gray-100">Nota:</span> Permohonan tidak terus membuka akses. Status akan menjadi 
            <span class="font-semibold text-warning-600">Menunggu Kelulusan</span> dan dihantar ke 
            <span class="font-semibold">Pengurusan Pengguna &rarr; Kelulusan Akses Modul</span> untuk semakan Pentadbir Utama. -->
            {!! __('my_access.access_note') !!}
        </div>

    </x-filament::section>
</x-filament-panels::page>
