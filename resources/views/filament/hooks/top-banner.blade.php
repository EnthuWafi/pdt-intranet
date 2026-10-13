@php
    //add 5 just to be safe
    $sessionLifetimeSeconds = (config('session.lifetime') * 60) + 5;
@endphp

<style>
    .bg-lines {
        background-image: 
            linear-gradient(135deg, transparent 0 33%, rgba(208, 211, 214, 0.32) 33% 43%, transparent 43%), 
            linear-gradient(135deg, transparent 0 45%, rgba(242, 214, 0, 0.36) 45% 48%, transparent 48%);
    }

    .dark .bg-lines {
        background-image: 
            linear-gradient(135deg, transparent 0 33%, rgba(255, 255, 255, 0.05) 33% 43%, transparent 43%), 
            linear-gradient(135deg, transparent 0 45%, rgba(242, 214, 0, 0.2) 45% 48%, transparent 48%);
    }
    .action-btn {
        transition: all 0.2s ease;
    }
    .action-btn:hover {
        transform: translateY(-2px);
        background-color: #2b2b2b;
    }
</style>


<livewire:announcement-bar />
<div class="bg-white bg-lines dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 shadow-sm p-6 flex flex-col lg:flex-row items-center justify-between gap-6">
    
    {{-- Left: Brand & Crest --}}
    <div class="flex items-center gap-5 w-full lg:w-auto">
        <img src="{{ Vite::asset('resources/images/perak.webp') }}" alt="Crest" class="h-20 w-20 sm:h-24 sm:w-24 object-contain drop-shadow-md shrink-0">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-1">
                {{ __('common.organization.portallong') }}
            </h1>
            <p class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">
                {{ __('common.organization.name') }}
            </p>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-300 border border-yellow-300 dark:border-yellow-700">
                {{ __('common.general.version') }} 1.0
            </span>
        </div>
    </div>

    {{-- Right: Profile Card & Action Quicklinks --}}
    <div class="flex items-center justify-end gap-3 w-full lg:w-auto ">
        
        {{-- Profile Card --}}
        <a 
            href="{{ filament()->getProfileUrl() }}" 
            class="md:flex hidden group border border-gray-200 dark:border-gray-800 hover:border-yellow-400 dark:hover:border-yellow-500 bg-gray-50 dark:bg-gray-800/60 hover:bg-yellow-50/30 dark:hover:bg-yellow-950/20 rounded-xl p-3 shadow-sm flex items-center gap-3 w-full sm:w-80 min-w-[280px] transition ease-in-out duration-150 shrink-0"
        >
            <div class="h-12 w-12 rounded-full bg-gray-200 dark:bg-gray-700 border-2 border-yellow-400 overflow-hidden shrink-0 shadow-inner">
                <img src="{{ auth()->user()->getFilamentAvatarUrl() }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
            </div>
            
            <div class="min-w-0 flex-1">
                <div class="flex items-center justify-between gap-1 mb-1">
                    <strong class="text-xs font-bold text-gray-900 dark:text-white truncate block group-hover:text-yellow-700 dark:group-hover:text-yellow-400">
                        {{ __('common.general.hello') }}, {{ auth()->user()->name }}
                    </strong>
                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full bg-green-50 dark:bg-green-950/40 border border-green-200 dark:border-green-800 text-[9px] font-extrabold text-green-700 dark:text-green-300 shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> {{ __('common.general.online') }}
                    </span>
                </div>
                <p class="text-[10px] text-gray-500 dark:text-gray-400 leading-tight truncate">
                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ auth()->user()->roles->first()?->name ?? __('common.general.unknown') }}</span><br>
                    <span>{{ now()->translatedFormat('l, d F Y') }}</span><br>
                    <span class="font-medium text-blue-700 dark:text-blue-300">{{ __('common.session.timeleft') }}

                    <span x-data="{
                        timeRemaining: {{ $sessionLifetimeSeconds }},
                        init() {
                            setInterval(() => {
                                if (this.timeRemaining > 0) {
                                    this.timeRemaining--;
                                } else {
                                    window.location.reload();
                                }
                            }, 1000);
                        },
                        formatTime() {

                            const visualTime = Math.max(0, this.timeRemaining - 5);
                            const hours = Math.floor(visualTime / 3600);
                            const minutes = Math.floor((visualTime % 3600) / 60);
                            const seconds = visualTime % 60;
                            
                            let display = '';
                            if (hours > 0) display += hours + ':';
                            display += minutes.toString().padStart(2, '0') + ':';
                            display += seconds.toString().padStart(2, '0');
                            
                            return display;
                        }
                    }" 
                    x-text="formatTime()" 

                    </span>
                    </span>
                </p>
            </div>
        </a>

        {{-- Action Buttons --}}
        <div x-data class="hidden md:flex items-center gap-2">
            <button 
                type="button"
                x-on:click="Livewire.dispatch('load-portal-page', { slug: '{{ __('common.slugs.help') }}' })"
                class="bg-gray-900 dark:bg-gray-800 hover:bg-neutral-800 dark:hover:bg-gray-700 text-white rounded-xl p-2.5 flex flex-col items-center justify-center w-[72px] h-[78px] transition duration-150 shrink-0 border border-transparent dark:border-gray-700"
            >
                <div class="w-8 h-8 rounded-lg bg-yellow-400 text-black flex items-center justify-center mb-1 shadow-sm">
                    <x-heroicon-o-question-mark-circle class="w-5 h-5 text-neutral-900 stroke-2" />
                </div>
                <span class="text-[9px] font-bold tracking-wider">{{ __('common.header.help') }}</span>
            </button>

            <button 
                type="button"
                x-on:click="Livewire.dispatch('load-portal-page', { slug: '{{ __('common.slugs.faq') }}' })"
                class="bg-gray-900 dark:bg-gray-800 hover:bg-neutral-800 dark:hover:bg-gray-700 text-white rounded-xl p-2.5 flex flex-col items-center justify-center w-[72px] h-[78px] transition duration-150 shrink-0 border border-transparent dark:border-gray-700"
            >
                <div class="w-8 h-8 rounded-lg bg-yellow-400 text-black flex items-center justify-center mb-1 shadow-sm">
                    <x-heroicon-o-chat-bubble-left-right class="w-5 h-5 text-neutral-900 stroke-2" />
                </div>
                <span class="text-[9px] font-bold tracking-wider">{{ __('common.header.faq') }}</span>
            </button>

            <button 
                type="button"
                x-on:click="Livewire.dispatch('load-portal-page', { slug: '{{ __('common.slugs.contact') }}' })"
                class="bg-gray-900 dark:bg-gray-800 hover:bg-neutral-800 dark:hover:bg-gray-700 text-white rounded-xl p-2.5 flex flex-col items-center justify-center w-[72px] h-[78px] transition duration-150 shrink-0 border border-transparent dark:border-gray-700"
            >
                <div class="w-8 h-8 rounded-lg bg-yellow-400 text-black flex items-center justify-center mb-1 shadow-sm">
                    <x-heroicon-o-phone class="w-5 h-5 text-neutral-900 stroke-2" />
                </div>
                <span class="text-[9px] font-bold tracking-wider">{{ __('common.header.contact') }}</span>
            </button>
        </div>

    </div>
</div>