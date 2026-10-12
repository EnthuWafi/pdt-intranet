<div>
    {{-- Footer Links --}}
    <footer class="portal-footer">
        <div x-data class="flex gap-4 text-xs text-neutral-500 justify-center py-4">
            <button type="button" x-on:click="Livewire.dispatch('load-portal-page', { slug: 'faq' })" class="hover:underline">FAQ</button>
            <span>•</span>
            <button type="button" x-on:click="Livewire.dispatch('load-portal-page', { slug: 'dasar-keselamatan' })" class="hover:underline">Dasar Keselamatan</button>
            <span>•</span>
            <button type="button" x-on:click="Livewire.dispatch('load-portal-page', { slug: 'dasar-privasi' })" class="hover:underline">Dasar Privasi</button>
            <span>•</span>
            <button type="button" x-on:click="Livewire.dispatch('load-portal-page', { slug: 'penafian' })" class="hover:underline">Penafian</button>
            <span>•</span>
            <button type="button" x-on:click="Livewire.dispatch('load-portal-page', { slug: 'hubungi-kami' })" class="hover:underline">Hubungi Kami</button>
        </div>
    </footer>
</div>