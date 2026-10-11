<div>
    <x-filament::modal id="portal-content-modal" width="3xl">
        <x-slot name="heading">
            {{ $page?->title ?? __('common.general.information') }}
        </x-slot>

        <div class="space-y-6 py-4">
            @if($page && !empty($page->blocks))
                @foreach($page->blocks as $block)
                    @switch($block['type'])
                        @case('heading')
                            @if($block['data']['level'] === 'h2')
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $block['data']['text'] }}</h2>
                            @elseif($block['data']['level'] === 'h3')
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $block['data']['text'] }}</h3>
                            @else
                                <h4 class="text-base font-medium text-gray-900 dark:text-white">{{ $block['data']['text'] }}</h4>
                            @endif
                            @break

                        @case('paragraph')
                            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">{{ $block['data']['content'] }}</p>
                            @break

                        @case('rich_text')
                            <div class="prose dark:prose-invert max-w-none text-sm">
                                {!! $block['data']['content'] !!}
                            </div>
                            @break

                        @case('faq_accordion')
                            @if(!empty($block['data']['faqs']))
                                <div class="space-y-4" x-data="{ activeAccordion: null }">
                                    @foreach($block['data']['faqs'] as $index => $faq)
                                        <div class="border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden bg-white dark:bg-gray-900">
                                            <button 
                                                type="button" 
                                                @click="activeAccordion = activeAccordion === {{ $index }} ? null : {{ $index }}"
                                                class="w-full px-4 py-3 text-left font-medium text-gray-900 dark:text-white flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-800/50 transition"
                                            >
                                                <span>{{ $faq['question'] }}</span>
                                                <x-heroicon-m-chevron-down class="w-5 h-5 text-gray-500 transition-transform duration-200" ::class="{ 'rotate-180': activeAccordion === {{ $index }} }" />
                                            </button>
                                            <div x-show="activeAccordion === {{ $index }}" x-collapse class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 border-t border-gray-100 dark:border-gray-800">
                                                {!! $faq['answer'] !!}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @break
                    @endswitch
                @endforeach
            @else
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    <p class="text-sm">Tiada kandungan tersedia untuk halaman ini buat masa sekarang.</p>
                </div>
            @endif
        </div>

        <x-slot name="footer">
            <div class="flex justify-between items-center w-full">
                <div>
                    @if($page && auth()->user()?->can('update', $page))
                        {{ $this->editContentAction }}
                    @endif
                </div>
                <x-filament::button color="gray" x-on:click="$dispatch('close-modal', { id: 'portal-content-modal' })">
                    Tutup
                </x-filament::button>
            </div>
        </x-slot>
    </x-filament::modal>
</div>
