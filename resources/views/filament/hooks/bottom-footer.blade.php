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

</div>