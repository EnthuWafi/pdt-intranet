<div>
    {{-- Footer Links --}}
    <footer class="portal-footer">
        <div class="flex gap-4 text-xs text-neutral-500 justify-center py-4">
            <button type="button" x-on:click="$dispatch('open-portal-page-modal', { slug: 'faq', title: 'FAQ' })" class="hover:underline">FAQ</button>
            <span>•</span>
            <button type="button" x-on:click="$dispatch('open-portal-page-modal', { slug: 'dasar-keselamatan', title: 'Dasar Keselamatan' })" class="hover:underline">Dasar Keselamatan</button>
            <span>•</span>
            <button type="button" x-on:click="$dispatch('open-portal-page-modal', { slug: 'dasar-privasi', title: 'Dasar Privasi' })" class="hover:underline">Dasar Privasi</button>
            <span>•</span>
            <button type="button" x-on:click="$dispatch('open-portal-page-modal', { slug: 'penafian', title: 'Penafian' })" class="hover:underline">Penafian</button>
            <span>•</span>
            <button type="button" x-on:click="$dispatch('open-portal-page-modal', { slug: 'hubungi-kami', title: 'Hubungi Kami' })" class="hover:underline">Hubungi Kami</button>
        </div>
    </footer>
</div>