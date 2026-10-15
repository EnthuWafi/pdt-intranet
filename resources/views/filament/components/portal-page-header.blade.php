@props([
    'title',
    'category' => null,
    'description' => null,
    'icon' => null,
    'actions' => [],
    'breadcrumbs' => [],
])

<div class="space-y-4 mb-6 ">
@if (! empty($breadcrumbs))
    <x-filament::breadcrumbs :breadcrumbs="$breadcrumbs" />
@endif

<div class="relative overflow-hidden rounded-2xl bg-white dark:bg-gray-900 border border-slate-200/80 dark:border-gray-800 shadow-sm p-6">
    {{-- Left Accent Line --}}
    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-yellow-400"></div>

    {{-- Right Decorative Arc --}}
    <div class="absolute right-0 top-0 bottom-0 w-44 bg-gradient-to-l from-yellow-100/40 dark:from-yellow-950/10 to-transparent rounded-l-full pointer-events-none"></div>

    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        {{-- Left: Icon & Text Details --}}
        <div class="flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-yellow-300 dark:bg-yellow-400 text-neutral-900 flex items-center justify-center shrink-0 shadow-sm">
                <x-filament::icon :icon="$icon" class="w-7 h-7 text-neutral-900 stroke-[2.2]" />
            </div>

            <div>
                @if($category)
                    <span class="block text-[11px] font-bold tracking-widest text-amber-600 dark:text-amber-400 uppercase mb-0.5">
                        {{ $category }}
                    </span>
                @endif

                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white leading-tight">
                    {{ $title }}
                </h1>

                @if($description)
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        {{ $description }}
                    </p>
                @endif
            </div>
        </div>

        {{-- Right: Native Filament Header Actions (e.g. "New User" Button) --}}
        @if(! empty($actions))
            <div class="shrink-0 flex items-center gap-3">
                <x-filament::actions :actions="$actions" />
            </div>
        @endif
    </div>
</div>
</div>