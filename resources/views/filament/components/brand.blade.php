<div class="flex items-center gap-2 mr-3">
    <img src="{{ Vite::asset('resources/images/perak.webp') }}" alt="Crest" class="h-8 w-8 object-contain">
    <span 
        class="font-extrabold text-sm tracking-tight transition-all duration-200 whitespace-nowrap" 
        x-show="$store.sidebar.isOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-x-2"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-cloak
    >
        {{ __('portal.portal_name') }}
    </span>
</div>