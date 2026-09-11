<x-filament-panels::page>
    {{-- Page content --}}
    <x-filament::section>
        
        <x-slot name="heading">
            Mohon Akses Modul
        </x-slot>
        <x-slot name="description">
            Pilih modul yang diperlukan. Peranan modul akan ditentukan oleh Pentadbir Utama semasa kelulusan.
        </x-slot>

        {{ $this->form }}

        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg text-sm text-gray-600 border-gray-200">
            <span class="font-bold text-gray-900 dark:text-white">Nota:</span> Permohonan tidak terus membuka akses. Status akan menjadi 
            <span class="font-semibold text-warning-600">Menunggu Kelulusan</span> dan dihantar ke 
            <span class="font-semibold">Pengurusan Pengguna &rarr; Kelulusan Akses Modul</span> untuk semakan Pentadbir Utama.
        </div>

    </x-filament::section>
</x-filament-panels::page>
