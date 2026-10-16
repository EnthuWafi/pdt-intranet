<x-filament-panels::page>
    <div
        wire:poll.3s="pollMessages"
        x-data="{
            scrollToBottom() {
                this.$nextTick(() => {
                    const container = this.$refs.messagesContainer;
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                });
            }
        }"
        x-init="scrollToBottom()"
        @chat-scroll-to-bottom.window="scrollToBottom()"
        class="flex h-[calc(100vh-12rem)] min-h-[600px] overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xs dark:border-white/10 dark:bg-gray-900"
    >
        {{-- Left Sidebar: User List --}}
        <div class="flex w-80 shrink-0 flex-col border-r border-gray-200 dark:border-white/10 sm:w-96">
            {{-- Sidebar Header --}}
            <div class="flex border-b border-gray-200 px-5 py-4 dark:border-white/10">
                <div class="flex flex-col">
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">
                        {{ __('chat_room.user_list_title') }}
                    </h2>
                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                        {{ __('chat_room.user_list_subtitle') }}
                    </p>
            </div>

                <!-- Right Side: Button -->
                <div class="flex shrink-0">
                    {{ ($this->createGroupAction)(['size' => 'sm']) }}
                </div>
            </div>

            {{-- Scrollable Users --}}
            <div class="flex-1 space-y-2 overflow-y-auto p-3">
                @foreach ($this->users as $user)
                    @php
                        $isSelected = $this->selectedUserId === $user->id;
                        $avatarUrl = method_exists($user, 'getFilamentAvatarUrl') ? $user->getFilamentAvatarUrl() : $user->avatar_url;
                        $roleName = $user->roles->first()?->name ?? __('common.general.unknown');
                        $unitName = $user->unit?->name;
                        $roleUnitLabel = collect([$roleName, $unitName])->filter()->join(' · ');
                        $isOnline = $user->last_seen && \Illuminate\Support\Carbon::parse($user->last_seen)->gt(now()->subMinutes(5));
                    @endphp

                    <button
                        type="button"
                        wire:key="chat-user-{{ $user->id }}"
                        wire:click="selectUser({{ $user->id }})"
                        @class([
                            'flex w-full items-center gap-3.5 rounded-xl border p-3 text-left transition duration-150',
                            'border-amber-400 bg-amber-50/30 ring-1 ring-amber-400/50 dark:border-amber-400 dark:bg-amber-500/10' => $isSelected,
                            'border-transparent hover:bg-gray-50 dark:hover:bg-white/5' => ! $isSelected,
                        ])
                    >
                        {{-- Avatar --}}
                        <div class="relative shrink-0">
                            @if ($avatarUrl)
                                <img
                                    src="{{ $avatarUrl }}"
                                    alt="{{ $user->name }}"
                                    class="h-11 w-11 rounded-full object-cover"
                                />
                            @else
                                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-neutral-900 text-xs font-bold text-white dark:bg-neutral-800">
                                    {{ $user->initials }}
                                </div>
                            @endif
                        </div>

                        {{-- User Details --}}
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <p class="truncate text-sm font-bold text-gray-900 dark:text-white">
                                    {{ $user->name }}
                                </p>

                                @if (($user->unread_messages_count ?? 0) > 0)
                                    <span class="inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-amber-400 px-1.5 text-[11px] font-bold text-gray-950">
                                        {{ $user->unread_messages_count }}
                                    </span>
                                @endif
                            </div>

                            @if ($roleUnitLabel)
                                <p class="truncate text-xs text-gray-500 dark:text-gray-400">
                                    {{ $roleUnitLabel }}
                                </p>
                            @endif

                            {{-- Online / Offline Indicator --}}
                            <div class="mt-1 flex items-center gap-1.5">
                                <span @class([
                                    'h-2 w-2 rounded-full',
                                    'bg-emerald-500' => $isOnline,
                                    'bg-gray-300 dark:bg-gray-600' => ! $isOnline,
                                ])></span>
                                <span class="text-[11px] text-gray-400 dark:text-gray-500">
                                    {{ $isOnline ? __('chat_room.status_online') : __('chat_room.status_offline') }}
                                </span>
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Right Area: Active Conversation --}}
        <div class="flex flex-1 flex-col bg-gray-50/40 dark:bg-gray-950/40">
            @if ($selectedUser = $this->selectedUser)
                @php
                    $selectedAvatarUrl = method_exists($selectedUser, 'getFilamentAvatarUrl') ? $selectedUser->getFilamentAvatarUrl() : $selectedUser->avatar_url;
                    $selectedRoleName = $selectedUser->roles->first()?->name ?? __('common.user', default: 'Pengguna');
                    $selectedUnitName = $selectedUser->unit?->name;
                    $selectedRoleUnitLabel = collect([$selectedRoleName, $selectedUnitName])->filter()->join(' · ');
                @endphp

                {{-- Conversation Top Bar --}}
                <div class="flex items-center gap-3.5 border-b border-gray-200 bg-white px-6 py-3.5 dark:border-white/10 dark:bg-gray-900">
                    @if ($selectedAvatarUrl)
                        <img
                            src="{{ $selectedAvatarUrl }}"
                            alt="{{ $selectedUser->name }}"
                            class="h-10 w-10 shrink-0 rounded-full object-cover"
                        />
                    @else
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-neutral-900 text-xs font-bold text-white dark:bg-neutral-800">
                            {{ $selectedUser->initials }}
                        </div>
                    @endif

                    <div class="min-w-0 flex-1">
                        <h3 class="truncate text-sm font-bold text-gray-900 dark:text-white">
                            {{ $selectedUser->name }}
                        </h3>
                        @if ($selectedRoleUnitLabel)
                            <p class="truncate text-xs text-gray-500 dark:text-gray-400">
                                {{ $selectedRoleUnitLabel }}
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Messages Feed --}}
                <div
                    x-ref="messagesContainer"
                    class="flex-1 space-y-4 overflow-y-auto p-6"
                >
                    @forelse ($this->messages as $msg)
                        @php
                            $isMine = $msg->sender_id === auth()->id();
                            $timestamp = $msg->created_at->format('d/m, h:i');
                            $meridiem = app()->getLocale() === 'ms'
                                ? ($msg->created_at->format('A') === 'AM' ? 'PG' : 'PTG')
                                : $msg->created_at->format('A');
                        @endphp

                        <div
                            wire:key="chat-msg-{{ $msg->id }}"
                            @class([
                                'flex w-full',
                                'justify-end' => $isMine,
                                'justify-start' => ! $isMine,
                            ])
                        >
                            <div @class([
                                'max-w-xl rounded-2xl border px-4 py-2.5 shadow-2xs',
                                'border-amber-300 bg-amber-50/90 text-gray-900 dark:border-amber-500/40 dark:bg-amber-500/15 dark:text-white' => $isMine,
                                'min-w-[18rem] sm:min-w-[26rem] border-gray-200 bg-white text-gray-900 dark:border-white/10 dark:bg-gray-900 dark:text-white' => ! $isMine,
                            ])>
                                <p class="whitespace-pre-wrap break-words text-sm leading-relaxed">{{ $msg->message }}</p>
                                <div class="mt-1.5 flex items-center justify-end gap-1">
                                    <span class="text-[10px] text-gray-400 dark:text-gray-500">
                                        {{ $timestamp }} {{ $meridiem }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex h-full items-center justify-center">
                            <p class="text-sm text-gray-400 dark:text-gray-500">
                                {{ __('chat_room.no_messages') }}
                            </p>
                        </div>
                    @endforelse
                </div>

                {{-- Bottom Message Input Bar --}}
                <div class="border-t border-gray-200 bg-white p-4 dark:border-white/10 dark:bg-gray-900">
                    <form
                        wire:submit="sendMessage"
                        class="flex items-end gap-3"
                    >
                        <div class="flex-1">
                            <textarea
                                wire:model="messageText"
                                rows="2"
                                placeholder="{{ __('chat_room.message_placeholder') }}"
                                @keydown.enter="if (!$event.shiftKey) { $event.preventDefault(); $wire.sendMessage(); }"
                                class="block w-full resize-y rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/30 focus:outline-none dark:border-white/15 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            ></textarea>
                        </div>

                        <button
                            type="submit"
                            class="inline-flex h-12 shrink-0 items-center justify-center gap-2 rounded-xl bg-amber-400 px-6 text-sm font-semibold text-gray-950 shadow-xs transition hover:bg-amber-300 focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 focus:outline-none disabled:opacity-50"
                        >
                            <x-filament::icon
                                icon="heroicon-o-paper-airplane"
                                class="h-4 w-4 -rotate-45 text-gray-950"
                            />
                            <span>{{ __('chat_room.send') }}</span>
                        </button>
                    </form>
                </div>
            @else
                {{-- Empty State when no user is selected --}}
                <div class="flex flex-1 flex-col items-center justify-center p-8 text-center">
                    <x-filament::icon
                        icon="heroicon-o-chat-bubble-left-right"
                        class="h-12 w-12 text-gray-300 dark:text-gray-600"
                    />
                    <h3 class="mt-3 text-sm font-semibold text-gray-900 dark:text-white">
                        {{ __('chat_room.no_user_selected_title') }}
                    </h3>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        {{ __('chat_room.no_user_selected_subtitle') }}
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>