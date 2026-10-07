<div class="flex items-center gap-2">
    <img src="{{ Vite::asset('resources/images/perak.webp') }}" alt="Crest" class="h-8 w-8 object-contain">
    <span class="font-extrabold text-sm tracking-tight text-gray-950 dark:text-white" x-show="$store.sidebar.isOpen"
    >{{ __('portal.portal_name') }}</span>
</div>