<?php

use App\Models\Announcement;
use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Filament\Resources\Announcements\AnnouncementResource;

new class extends Component
{
    #[Computed]
    public function announcements()
    {
        return Announcement::active()->latest()->get();
    }

    public static function getUrl(Announcement $announcement) 
    {
        return AnnouncementResource::getUrl('view', ['record' => $announcement]);
    }
};
?>

<div>
    {{-- The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk --}}
    <style scoped>
        @keyframes dynamic-marquee {
            0% { transform: translate3d(0, 0, 0); }
            100% { transform: translate3d(-50%, 0, 0); }
        }
    </style>
    <div wire:poll.60s class="bg-gray-50-400 text-neutral-900 dark:text-white overflow-hidden text-xs">
    
    <div class="max-w-7xl mx-auto px-4 py-2.5 flex items-center gap-3">
        <!-- Label -->
        <span class="z-10 font-bold uppercase tracking-wider text-[10px]  rounded shrink-0">
            Pengumuman
        </span>
        <div class="h-4 w-px bg-gray-300 mx-1"></div>
        <div class="px-1 py-1 bg-amber-300 rounded shadow-sm">
            <span>🔊</span>
        </div>
        <!-- Dynamic Marquee Wrapper -->
        <div 
            class="relative flex-1 overflow-hidden"
            x-data="{ 
                isHovered: false,
                speed: 40,
                speedMultiplier: 60,
                init() {
                    this.adjustTrack();
                    
                    // Trigger recalculations only when the tab becomes active again
                    document.addEventListener('visibilitychange', () => {
                        if (!document.hidden) {
                            this.adjustTrack();
                        }
                    });

                    // Listens for Livewire refreshes cleanly without scrambling DOM trees
                    Livewire.hook('morph.updated', () => {
                        this.adjustTrack();
                    });
                },
                adjustTrack() {
                    this.$nextTick(() => {
                        let singleTrack = this.$refs.singleTrack;
                        let marqueeTrack = this.$refs.marqueeTrack;
                        let wrapper = this.$root;
                        
                        if (!singleTrack || !marqueeTrack || !wrapper) return;

                        // Safely remove previous loop clones without touching Livewire elements
                        let clones = marqueeTrack.querySelectorAll('.marquee-clone');
                        clones.forEach(c => c.remove());

                        let singleWidth = singleTrack.offsetWidth;
                        let wrapperWidth = wrapper.offsetWidth;
                        
                        // If browser tab throttling returns 0, retry briefly after rendering catches up
                        if (singleWidth === 0) {
                            setTimeout(() => this.adjustTrack(), 100);
                            return;
                        }

                        // Determine duplication density loops
                        let neededClones = Math.ceil((wrapperWidth * 2) / singleWidth);
                        neededClones = Math.max(neededClones, 1);

                        // Inject content mirror copies safely
                        for (let i = 0; i < neededClones; i++) {
                            let clone = singleTrack.cloneNode(true);
                            clone.classList.add('marquee-clone');
                            clone.removeAttribute('x-ref'); // Strip structural refs to avoid tracking conflict
                            clone.setAttribute('aria-hidden', 'true');
                            marqueeTrack.appendChild(clone);
                        }

                        // Reset velocity rules dynamically
                        let totalWidth = marqueeTrack.offsetWidth;
                        this.speed = Math.max(Math.round(totalWidth / this.speedMultiplier), 10);
                    });
                }
            }"
            @mouseenter="isHovered = true"
            @mouseleave="isHovered = false"
        >
            <!-- wire:ignore stops Livewire from rewriting or clearing our runtime modifications -->
            <div 
                x-ref="marqueeTrack"
                wire:ignore
                class="flex w-max min-w-full whitespace-nowrap"
                :style="{ 
                    animation: 'dynamic-marquee ' + speed + 's linear infinite', 
                    animationPlayState: isHovered ? 'paused' : 'running' 
                }"
            >
                <!-- Base Announcement Track -->
                <div x-ref="singleTrack" class="flex items-center gap-12 pr-12">
                    @if($this->announcements->isNotEmpty())
                        @foreach($this->announcements as $announcement)
                        <span class="font-medium flex items-center gap-2" style="color: var(--{{ $announcement->type->getColor() }}-600);">
                                @svg($announcement->type->getIcon(), 'w-5 h-5 text-custom-600')
                                <a class="text-gray-800 dark:text-white" href="{{ $this->getUrl($announcement) }}">
                                    {{ $announcement->type->getLabel() }} - {{ $announcement->title }}
                                </a>
                            </span>
                        @endforeach
                    @else
                        <span class="font-medium">Tiada pengumuman buat masa ini.</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
