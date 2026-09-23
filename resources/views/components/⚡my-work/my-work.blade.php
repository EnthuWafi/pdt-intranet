<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm overflow-hidden mb-6">
    {{-- Tabs Navigation --}}
    <div class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50 px-6 py-3 flex items-center justify-between flex-wrap gap-4">
        <h2 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <x-heroicon-o-clipboard-document-list class="w-5 h-5 text-yellow-500" />
            Kerja Saya
        </h2>

        <div class="flex items-center gap-1 bg-gray-200 dark:bg-gray-800 p-1 rounded-xl">
            {{-- Tab: Peti Tindakan --}}
            <button 
                type="button"
                wire:click="setTab('tindakan')"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 {{ $activeTab === 'tindakan' ? 'bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900' }}"
            >
                Peti Tindakan
                @if($pendingCount > 0)
                    <span class="px-1.5 py-0.2 bg-red-500 text-white rounded-full text-[10px] font-bold">
                        {{ $pendingCount }}
                    </span>
                @endif
            </button>

            {{-- Tab: Notifikasi --}}
            <button 
                type="button"
                wire:click="setTab('notifikasi')"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 {{ $activeTab === 'notifikasi' ? 'bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900' }}"
            >
                Notifikasi
                @if($unreadNotificationCount > 0)
                    <span class="px-1.5 py-0.2 bg-yellow-500 text-black rounded-full text-[10px] font-bold">
                        {{ $unreadNotificationCount }}
                    </span>
                @endif
            </button>

            {{-- Tab: Aktiviti Saya --}}
            <button 
                type="button"
                wire:click="setTab('aktiviti')"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $activeTab === 'aktiviti' ? 'bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900' }}"
            >
                Aktiviti Saya
            </button>
        </div>
    </div>

    {{-- Content Panels --}}
    <div class="p-6">
        {{-- 1. Peti Tindakan --}}
        @if($activeTab === 'tindakan')
            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($pendingRequests as $req)
                    <div class="py-3 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-yellow-100 dark:bg-yellow-950/40 text-yellow-800 dark:text-yellow-400 flex items-center justify-center shrink-0">
                                <x-heroicon-o-key class="w-5 h-5" />
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-gray-900 dark:text-white truncate">
                                    {{ $req->user->name }} memohon akses: <span class="text-yellow-600 dark:text-yellow-400">{{ $req->module->name }}</span>
                                </p>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate">
                                    Tujuan: {{ $req->purpose ?? '-' }} • {{ $req->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <button 
                                wire:click="approveRequest({{ $req->id }})"
                                class="px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-black text-xs font-bold rounded-lg transition"
                            >
                                Luluskan
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-gray-400">
                        Tiada tindakan tertunggak pada masa ini.
                    </div>
                @endforelse
            </div>
            @if(method_exists($pendingRequests, 'hasPages') && $pendingRequests->hasPages())
                <div class="mt-4 border-t border-gray-100 dark:border-gray-800 pt-3">
                    {{ $pendingRequests->links() }}
                </div>
            @endif
        @endif

        {{-- 2. Notifikasi --}}
        @if($activeTab === 'notifikasi')
            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($notifications as $notif)
                    <div class="py-3 flex items-center justify-between gap-4 {{ $notif->read_at ? 'opacity-70' : '' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-full bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                                <x-heroicon-o-bell class="w-4 h-4" />
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-gray-900 dark:text-white">
                                    {{ $notif->data['title'] ?? 'Pemberitahuan Sistem' }}
                                </p>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400">
                                    {{ $notif->data['message'] ?? '' }} • {{ $notif->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        @if(is_null($notif->read_at))
                            <button 
                                wire:click="markNotificationAsRead('{{ $notif->id }}')"
                                class="text-[10px] text-yellow-600 dark:text-yellow-400 hover:underline shrink-0"
                            >
                                Tanda Dibaca
                            </button>
                        @endif
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-gray-400">
                        Tiada notifikasi baru.
                    </div>
                @endforelse
            </div>
            @if(method_exists($notifications, 'hasPages') && $notifications->hasPages())
                <div class="mt-4 border-t border-gray-100 dark:border-gray-800 pt-3">
                    {{ $notifications->links() }}
                </div>
            @endif
        @endif

        {{-- 3. Aktiviti Saya --}}
        @if($activeTab === 'aktiviti')
            <div class="relative border-l border-gray-200 dark:border-gray-800 ml-4 space-y-4 py-2">
                @forelse($activities as $act)
                    <div class="relative pl-6">
                        <span class="absolute -left-1.5 top-1 w-3 h-3 rounded-full bg-yellow-400 border-2 border-white dark:border-gray-900"></span>
                        <p class="text-xs font-semibold text-gray-900 dark:text-white">
                            {{ $act->description }}
                        </p>
                        <span class="text-[10px] text-gray-400">
                            {{ $act->created_at->translatedFormat('d M Y, h:i A') }} ({{ $act->created_at->diffForHumans() }})
                        </span>
                    </div>
                @empty
                    <div class="py-6 text-center text-xs text-gray-400">
                        Tiada rekod aktiviti setakat ini.
                    </div>
                @endforelse
            </div>
            @if(method_exists($activities, 'hasPages') && $activities->hasPages())
                <div class="mt-4 border-t border-gray-100 dark:border-gray-800 pt-3">
                    {{ $activities->links() }}
                </div>
            @endif
        @endif
    </div>
</div>