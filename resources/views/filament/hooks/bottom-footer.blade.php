<div>
    {{-- Footer Links --}}
    <footer class="portal-footer">
        <div class="flex gap-4 text-xs text-neutral-500 justify-center py-4">
            <button type="button" x-on:click="$dispatch('open-portal-page-modal', { slug: 'portal-banner' })" class="hover:underline">FAQ</button>
            <span>•</span>
            <button type="button" x-on:click="$dispatch('open-portal-page-modal', { slug: 'portal-banner' })" class="hover:underline">Dasar Keselamatan</button>
            <span>•</span>
            <button type="button" x-on:click="$dispatch('open-portal-page-modal', { slug: 'portal-banner' })" class="hover:underline">Dasar Privasi</button>
            <span>•</span>
            <button type="button" x-on:click="$dispatch('open-portal-page-modal', { slug: 'portal-banner' })" class="hover:underline">Penafian</button>
            <span>•</span>
            <button type="button" class="hover:underline">Hubungi Kami</button>
        </div>
    </footer>

    {{-- Content Modal --}}
    @if($activeModalSlug)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div class="bg-white dark:bg-neutral-900 rounded-xl shadow-xl max-w-2xl w-full p-6 space-y-4 max-h-[85vh] overflow-y-auto">
                <div class="flex items-center justify-between border-b pb-3">
                    <h3 class="text-base font-bold text-neutral-800 dark:text-white">
                        {{ $currentPage?->title ?? ucwords(str_replace('-', ' ', $activeModalSlug)) }}
                    </h3>

                    <div class="flex items-center gap-2">
                        {{-- In-place edit button for admins --}}
                        {{ $this->editPageAction }}

                        <button wire:click="closeModal" class="text-neutral-400 hover:text-neutral-600">
                            <x-heroicon-o-x-mark class="w-5 h-5" />
                        </button>
                    </div>
                </div>

                <div class="prose dark:prose-invert max-w-none text-sm text-justify">
                    {!! $currentPage?->content ?? '<p class="text-neutral-400 italic">Tiada kandungan direkodkan.</p>' !!}
                </div>
            </div>
        </div>
    @endif

    <x-filament-actions::modals />
</div>