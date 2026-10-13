<div>
    <x-filament::modal id="portal-content-modal" width="5xl" :close-button="false">
        {{-- Custom Modal Header --}}
        <div class="relative overflow-hidden -mx-6 -mt-6 p-6 pb-5 border-b border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 rounded-t-2xl">
            {{-- Diagonal Accent Bar in Top-Right --}}
            <div class="absolute -top-6 right-36 w-6 h-28 bg-yellow-300 dark:bg-yellow-400 rotate-45 pointer-events-none opacity-80"></div>

            <div class="flex items-start justify-between gap-4 relative z-10">
                <div class="flex items-start gap-4">
                    {{-- Left Yellow Square Badge --}}
                    <div class="w-12 h-12 shrink-0 rounded-2xl bg-yellow-400 flex items-center justify-center text-black shadow-sm">
                        @php
                            $iconName = $this->page?->icon ?? 'heroicon-o-question-mark-circle';
                        @endphp
                        <x-filament::icon
                            :icon="$iconName"
                            class="w-7 h-7 text-neutral-900"
                        />
                    </div>

                    {{-- Title & Subtitle Group --}}
                    <div>
                        <span class="block text-[11px] font-bold tracking-widest text-amber-700 dark:text-amber-400 uppercase">
                            {{ __('common.organization.portallong') }}
                        </span>
                        <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight leading-tight mt-0.5">
                            {{ $this->page?->title ?? __('common.general.information') }}
                        </h2>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $this->page->description ?? '' }}
                        </p>
                    </div>
                </div>

                {{-- Action Buttons (Edit + Close) --}}
                <div class="flex items-center gap-2 shrink-0">
                    @if($this->page && auth()->user()?->can('update', $this->page))
                        {{ $this->editContentAction }}
                    @endif

                    <button 
                        type="button" 
                        x-on:click="$dispatch('close-modal', { id: 'portal-content-modal' })"
                        class="w-10 h-10 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 transition"
                    >
                        <x-heroicon-m-x-mark class="w-5 h-5" />
                    </button>
                </div>
            </div>
        </div>

        {{-- Modal Body: Blocks Rendering --}}
        <div class="py-3 space-y-2">
            @if($this->page && !empty($this->page->blocks))
                @foreach($this->page->blocks as $block)
                    @switch($block['type'])

                        {{-- 2-Column Info Cards Grid --}}
                        @case('info_cards')
                            @if(!empty($block['data']['cards']))
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full my-2">
                                    @foreach($block['data']['cards'] as $card)
                                        <div class="flex items-start gap-4 p-5 sm:p-6 rounded-2xl border border-slate-200 bg-white shadow-sm h-full">
                                            
                                            {{-- Yellow Icon Box --}}
                                            <div class="w-11 h-11 min-w-[44px] rounded-xl bg-yellow-400 flex items-center justify-center shrink-0">
                                                @switch($card['icon'] ?? 'help')
                                                    @case('key')
                                                        <x-heroicon-m-key class="w-5 h-5 text-slate-900" />
                                                        @break
                                                    @case('shield')
                                                        <x-heroicon-m-shield-check class="w-5 h-5 text-slate-900" />
                                                        @break
                                                    @case('plus')
                                                        <x-heroicon-m-plus class="w-5 h-5 text-slate-900 stroke-2" />
                                                        @break
                                                    @case('bell')
                                                        <x-heroicon-m-bell class="w-5 h-5 text-slate-900" />
                                                        @break
                                                    @case('arrows')
                                                        <x-heroicon-m-arrows-right-left class="w-5 h-5 text-slate-900" />
                                                        @break
                                                    @case('chat')
                                                        <x-heroicon-m-chat-bubble-left-right class="w-5 h-5 text-slate-900" />
                                                        @break
                                                    @case('alert')
                                                        <x-heroicon-m-exclamation-circle class="w-5 h-5 text-slate-900" />
                                                        @break
                                                    @default
                                                        <x-heroicon-m-question-mark-circle class="w-5 h-5 text-slate-900" />
                                                @endswitch
                                            </div>

                                            {{-- Text Content --}}
                                            <div class="flex-1 min-w-0 flex flex-col gap-1 pt-0.5">
                                                <h3 class="text-[15px] font-bold text-slate-900 leading-snug m-0">
                                                    {{ $card['title'] }}
                                                </h3>
                                                <p class="text-[13px] text-slate-500 leading-relaxed m-0 font-normal">
                                                    {{ $card['description'] }}
                                                </p>
                                            </div>

                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @break
                        @case('heading')
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $block['data']['text'] ?? '' }}</h2>
                            @break

                        @case('paragraph')
                            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">{{ $block['data']['content'] ?? '' }}</p>
                            @break

                        @case('rich_text')
                            <div class="prose dark:prose-invert max-w-none text-sm">
                                {!! $block['data']['content'] ?? '' !!}
                            </div>
                            @break

                        @case('accordion')
                            @if(!empty($block['data']['faqs']))
                                <div class="space-y-3" x-data="{ activeAccordion: null }">
                                    @foreach($block['data']['faqs'] as $index => $faq)
                                        <div class="border border-slate-200 dark:border-gray-800 rounded-2xl overflow-hidden bg-white dark:bg-gray-900 transition-colors">
                                            {{-- Accordion Header / Toggle Button --}}
                                            <button 
                                                type="button" 
                                                @click="activeAccordion = activeAccordion === {{ $index }} ? null : {{ $index }}"
                                                class="w-full px-5 py-4 text-left font-semibold text-slate-900 dark:text-white flex justify-between items-center hover:bg-slate-50 dark:hover:bg-gray-800/50 transition-colors gap-4 select-none"
                                            >
                                                <span class="text-sm sm:text-base leading-snug">{{ $faq['question'] ?? '' }}</span>
                                                
                                                <x-heroicon-m-chevron-down 
                                                    class="w-5 h-5 text-slate-400 shrink-0 transition-transform duration-200 ease-out" 
                                                    ::class="{ 'rotate-180': activeAccordion === {{ $index }} }" 
                                                />
                                            </button>

                                            {{-- Collapsing Wrapper (NO vertical padding here) --}}
                                            <div 
                                                x-show="activeAccordion === {{ $index }}" 
                                                x-collapse.duration.250ms
                                                x-cloak
                                                class="border-t border-slate-100 dark:border-gray-800"
                                            >
                                                {{-- Inner Content Container (Padding goes here) --}}
                                                <div class="px-5 py-4 text-sm text-slate-600 dark:text-gray-300 leading-relaxed font-normal">
                                                    {!! $faq['answer'] ?? '' !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @break
                    @endswitch
                @endforeach
            @else
                <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                    <p class="text-sm">Tiada kandungan tersedia untuk halaman ini.</p>
                </div>
            @endif
        </div>
    </x-filament::modal>

    <x-filament-actions::modals />
</div>