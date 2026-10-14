<footer class="w-full bg-white dark:bg-gray-900 border-t border-slate-200 dark:border-gray-800 relative select-none">
    {{-- Two-Tone Top Accent Bar --}}
    <div class="h-1 w-full flex">
        <div class="w-1/3 bg-yellow-400"></div>
        <div class="w-2/3 bg-slate-900 dark:bg-slate-950"></div>
    </div>

    {{-- Main Footer Content --}}
    <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            
            {{-- Column 1: Organization Info & Map --}}
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-yellow-400 text-slate-950 flex items-center justify-center shrink-0 shadow-sm">
                        <x-lucide-command class="w-6 h-6" />
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold tracking-widest text-amber-600 dark:text-amber-400 uppercase">
                            {{ __('common.organization.portallong') }}
                        </span>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white leading-tight">
                            {{ __('common.organization.name') }}
                        </h3>
                    </div>
                </div>

                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                    Manjung District Administration Complex, 32040 Seri Manjung, Perak Darul Ridzuan.
                </p>

                <div class="text-xs font-black tracking-wider text-slate-900 dark:text-white flex items-center gap-2">
                    <span>MANJUNG</span>
                    <span class="text-yellow-500 font-bold">|</span>
                    <span>PROSPEROUS</span>
                    <span class="text-yellow-500 font-bold">|</span>
                    <span>DEVELOPING</span>
                </div>

                {{-- Interactive / Embedded Map Card --}}
                <div class="relative w-full h-36 rounded-xl overflow-hidden border border-slate-200 dark:border-gray-800 shadow-sm">
                    <iframe 
                        title="Manjung District and Land Office Location Map"
                        src="https://www.google.com/maps?q=Pejabat+Daerah+dan+Tanah+Manjung,+Kompleks+Pentadbiran+Daerah+Manjung,+32040+Seri+Manjung,+Perak&output=embed"
                        class="w-full h-full border-0"
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                </div>
            </div>

            {{-- Column 2: Media Sosial --}}
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl border border-yellow-300 dark:border-yellow-600/50 bg-yellow-50 dark:bg-yellow-950/20 text-yellow-700 dark:text-yellow-400 flex items-center justify-center shrink-0">
                        <x-heroicon-m-globe-alt class="w-5 h-5" />
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold tracking-widest text-amber-600 dark:text-amber-400 uppercase">
                            {{ __('common.footer.follow_us') }}
                        </span>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white leading-tight">
                            {{ __('common.footer.social_media') }}
                        </h3>
                    </div>
                </div>

                <div class="space-y-2">
                    {{-- Facebook --}}
                    <a 
                        href="https://facebook.com/pdtmanjung" 
                        target="_blank" 
                        rel="noopener noreferrer" 
                        class="flex items-center justify-between p-2.5 rounded-xl border border-slate-100 dark:border-gray-800 hover:bg-slate-50 dark:hover:bg-gray-800/50 transition group"
                    >
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                                f
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-slate-900 dark:text-white leading-tight">Facebook</span>
                                <span class="block text-[11px] text-slate-500">@pdtmanjung</span>
                            </div>
                        </div>
                        <x-heroicon-m-arrow-up-right class="w-3.5 h-3.5 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200 transition" />
                    </a>

                    {{-- Instagram --}}
                    <a 
                        href="https://instagram.com/pdtmanjung" 
                        target="_blank" 
                        rel="noopener noreferrer" 
                        class="flex items-center justify-between p-2.5 rounded-xl border border-slate-100 dark:border-gray-800 hover:bg-slate-50 dark:hover:bg-gray-800/50 transition group"
                    >
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center">
                                <x-heroicon-o-camera class="w-4 h-4" />
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-slate-900 dark:text-white leading-tight">Instagram</span>
                                <span class="block text-[11px] text-slate-500">@pdtmanjung</span>
                            </div>
                        </div>
                        <x-heroicon-m-arrow-up-right class="w-3.5 h-3.5 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200 transition" />
                    </a>

                    {{-- X (Twitter) --}}
                    <a 
                        href="https://x.com/PDTManjung" 
                        target="_blank" 
                        rel="noopener noreferrer" 
                        class="flex items-center justify-between p-2.5 rounded-xl border border-slate-100 dark:border-gray-800 hover:bg-slate-50 dark:hover:bg-gray-800/50 transition group"
                    >
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-gray-800 text-slate-900 dark:text-white flex items-center justify-center font-bold text-xs">
                                𝕏
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-slate-900 dark:text-white leading-tight">X</span>
                                <span class="block text-[11px] text-slate-500">@PDTManjung</span>
                            </div>
                        </div>
                        <x-heroicon-m-arrow-up-right class="w-3.5 h-3.5 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200 transition" />
                    </a>
                </div>
            </div>

            {{-- Column 3: Bantuan Rasmi / Hubungi Kami --}}
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl border border-yellow-300 dark:border-yellow-600/50 bg-yellow-50 dark:bg-yellow-950/20 text-yellow-700 dark:text-yellow-400 flex items-center justify-center shrink-0">
                        <x-heroicon-m-phone class="w-5 h-5" />
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold tracking-widest text-amber-600 dark:text-amber-400 uppercase">
                            {{ __('common.footer.official_help') }}
                        </span>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white leading-tight">
                            {{ __('common.footer.contact_us') }}
                        </h3>
                    </div>
                </div>

                <div class="space-y-2">
                    {{-- Email --}}
                    <div class="flex items-center gap-3 p-2.5 rounded-xl border border-slate-100 dark:border-gray-800">
                        <div class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-gray-800 flex items-center justify-center text-slate-600 dark:text-slate-300 shrink-0">
                            <x-heroicon-m-envelope class="w-3.5 h-3.5" />
                        </div>
                        <a href="mailto:pdtmanjung@perak.gov.my" class="text-xs font-medium text-slate-700 dark:text-slate-300 hover:underline truncate">
                            pdtmanjung@perak.gov.my
                        </a>
                    </div>

                    {{-- Telephone --}}
                    <div class="flex items-center gap-3 p-2.5 rounded-xl border border-slate-100 dark:border-gray-800">
                        <div class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-gray-800 flex items-center justify-center text-slate-600 dark:text-slate-300 shrink-0">
                            <x-heroicon-m-phone class="w-3.5 h-3.5" />
                        </div>
                        <span class="text-xs font-medium text-slate-700 dark:text-slate-300">
                            +05 698 2000
                        </span>
                    </div>

                    {{-- Fax --}}
                    <div class="flex items-center gap-3 p-2.5 rounded-xl border border-slate-100 dark:border-gray-800">
                        <div class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-gray-800 flex items-center justify-center text-slate-600 dark:text-slate-300 shrink-0">
                            <x-heroicon-m-printer class="w-3.5 h-3.5" />
                        </div>
                        <span class="text-xs font-medium text-slate-700 dark:text-slate-300">
                            +05 698 2050
                        </span>
                    </div>

                    {{-- Maklum Balas Button --}}
                    <button 
                        type="button" 
                        onclick="Livewire.dispatch('open-feedback-modal')"
                        class="w-full mt-1 px-4 py-2.5 rounded-xl border border-yellow-400 bg-yellow-50/50 dark:bg-yellow-950/20 text-slate-800 dark:text-yellow-400 text-xs font-semibold flex items-center justify-center gap-2 hover:bg-yellow-100/60 dark:hover:bg-yellow-900/30 transition shadow-sm"
                    >
                        <x-heroicon-m-chat-bubble-bottom-center-text class="w-4 h-4 text-yellow-600 dark:text-yellow-400" />
                        <span>{{ __('common.footer.give_feedback') }}</span>
                    </button>

                    <livewire:feedback-modal />
                </div>
            </div>

            {{-- Column 4: Rujukan Sistem / Pautan --}}
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl border border-yellow-300 dark:border-yellow-600/50 bg-yellow-50 dark:bg-yellow-950/20 text-yellow-700 dark:text-yellow-400 flex items-center justify-center shrink-0">
                        <x-heroicon-m-document-duplicate class="w-5 h-5" />
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold tracking-widest text-amber-600 dark:text-amber-400 uppercase">
                            {{ __('common.footer.system_reference') }}
                        </span>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white leading-tight">
                            {{ __('common.footer.links') }}
                        </h3>
                    </div>
                </div>

                <div class="space-y-2">
                    @php
                        $links = [
                            ['label' => __('common.footer.security_policy'), 'slug' => 'dasar-keselamatan'],
                            ['label' => __('common.footer.privacy_policy'), 'slug' => 'dasar-privasi'],
                            ['label' => __('common.footer.disclaimer'), 'slug' => 'penafian'],
                        ];
                    @endphp

                    @foreach($links as $item)
                        <button 
                            type="button" 
                            onclick="Livewire.dispatch('load-portal-page', { slug: '{{ $item['slug'] }}' })"
                            class="w-full flex items-center gap-3 p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-gray-800/50 transition group text-left"
                        >
                            <div class="w-6 h-6 rounded-lg border border-yellow-400 bg-yellow-50 dark:bg-yellow-950/30 flex items-center justify-center text-yellow-700 dark:text-yellow-400 shrink-0">
                                <x-heroicon-m-chevron-right class="w-3.5 h-3.5 stroke-2" />
                            </div>
                            <span class="text-xs font-medium text-slate-700 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white transition">
                                {{ $item['label'] }}
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <div class="w-full bg-[#0d121c] text-slate-400 text-[11px] py-2 px-6 border-t border-slate-800">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-center text-center gap-x-2 gap-y-1">
            <span class="text-yellow-400 font-bold">{{ __('common.organization.portallong') }} v1.0</span>
            <span>|</span>
            <span>{{ __('common.footer.last_updated') }}</span>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full border border-slate-700 bg-slate-800/80 text-slate-200 text-[10px]">
                <x-heroicon-m-clock class="w-3 h-3 text-slate-400" />
                13th October 2026
            </span>
            <span>|</span>
            <span>{{ __('common.footer.all_rights_reserved') }} © 2026 {{ __('common.organization.name') }}</span>
        </div>
    </div>
</footer>