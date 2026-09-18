<?php

use App\Models\Announcement;
use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component
{
    #[Computed]
    public function announcements()
    {
        return Announcement::active()->latest()->get();
    }
};
?>



<div>
    {{-- The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk --}}
    <div wire:poll.60s class="bg-amber-400 text-neutral-900 border-b border-amber-500 overflow-hidden text-xs">
    @if($this->announcements->isNotEmpty())
        <div class="max-w-7xl mx-auto px-4 py-1.5 flex items-center gap-3">
            <span class="font-bold uppercase tracking-wider text-[10px] bg-neutral-900 text-white px-2 py-0.5 rounded">
                Pengumuman
            </span>

            {{-- Alpine.js Ticker --}}
            <div 
                x-data="{ 
                    active: 0, 
                    total: {{ $this->announcements->count() }},
                    next() { this.active = (this.active + 1) % this.total }
                }"
                x-init="if (total > 1) setInterval(() => next(), 5000)"
                class="relative h-5 overflow-hidden flex-1"
            >
                @foreach($this->announcements as $index => $announcement)
                    <div 
                        x-show="active === {{ $index }}"
                        x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="translate-y-4 opacity-0"
                        x-transition:enter-end="translate-y-0 opacity-100"
                        x-transition:leave="transition ease-in duration-200 transform"
                        x-transition:leave-start="translate-y-0 opacity-100"
                        x-transition:leave-end="-translate-y-4 opacity-0"
                        class="absolute inset-0 flex items-center font-medium truncate"
                    >
                        {{ $announcement->message }}
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="max-w-7xl mx-auto px-4 py-1.5 flex items-center gap-3">
            <span class="font-bold uppercase tracking-wider text-[10px] bg-neutral-500 text-white px-2 py-0.5 rounded not-italic">
                Pengumuman
            </span>
            
            <span>No announcements currently.</span>
        </div>
    @endif
    </div>
</div>