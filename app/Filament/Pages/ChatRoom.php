<?php

namespace App\Filament\Pages;

use App\Enums\UserStatus;
use App\Models\ChatMessage;
use App\Models\ChatRoomParticipant;
use App\Models\User;
use App\Traits\HasSpecialHeader;
use BackedEnum;
use App\Models\ChatRoom as ChatRoomModel;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

class ChatRoom extends Page
{
    use HasSpecialHeader;
    protected string $view = 'filament.pages.chat-room';

    protected static string | BackedEnum | null $navigationIcon = Heroicon::ChatBubbleLeftRight;
    protected static ?int $navigationSort = 3;

    #[Url(as: 'chat-room')]
    public ?int $selectedChatRoomId = null;

    public ?int $selectedUserId = null;

    public string $messageText = '';

      public static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.my_work');
    }

    public static function getNavigationLabel(): string
    {
        return __('chat_room.title');
    }

    public function getTitle(): string
    {
        return __('chat_room.title');
    }
    
    public function getHeaderDescription(): string | Htmlable | null
    {
        return __('chat_room.subtitle');
    }

    public static function getNavigationBadge(): ?string
    {
        $userId = Auth::id();

        if (! $userId) {
            return null;
        }

        $unreadCount = ChatMessage::unreadForUser($userId)->count();

        return $unreadCount > 0 ? (string) $unreadCount : null;
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'warning';
    }

    public function getBreadcrumbs(): array
    {
        return [
            '' => static::getNavigationLabel(),
        ];
    }

    // When first loads show the first user chat on the list
    public function mount(): void
    {
        if ($this->selectedChatRoomId) {
            $room = ChatRoomModel::with('participants')->find($this->selectedChatRoomId);
            if ($room && ! $room->is_group) {
                $this->selectedUserId = $room->participants
                    ->firstWhere('user_id', '!=', Auth::id())?->user_id;
            }
            $this->markRoomAsRead();
        } elseif ($firstUser = $this->users->first()) {
            $this->selectUser($firstUser->id);
        }
    }

    #[Computed]
    public function users(): Collection
    {
        $authId = Auth::id();

        return User::query()
            ->where('id', '!=', $authId)
            ->where('status', UserStatus::ACTIVE)
            ->with(['roles', 'unit'])
            ->addSelect([
                'unread_messages_count' => ChatMessage::query()
                    ->selectRaw('COUNT(*)')
                    ->join(
                        'chat_room_participants as my_part',
                        'chat_messages.chat_room_id',
                        '=',
                        'my_part.chat_room_id'
                    )
                    ->join(
                        'chat_rooms',
                        'chat_messages.chat_room_id',
                        '=',
                        'chat_rooms.id'
                    )
                    ->where('chat_rooms.is_group', false)
                    ->where('my_part.user_id', $authId)
                    ->whereColumn('chat_messages.sender_id', 'users.id')
                    ->where(function ($q) {
                        $q->whereColumn('chat_messages.created_at', '>', 'my_part.last_read_at')
                            ->orWhereNull('my_part.last_read_at');
                    }),
            ])
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function selectUser(int $targetUserId): void
    {
        $authId = Auth::id();
        $this->selectedUserId = $targetUserId;

        // Find existing 1-on-1 room between auth user and target user
        $room = ChatRoomModel::query()
            ->where('is_group', false)
            ->whereHas('participants', fn ($q) => $q->where('user_id', $authId))
            ->whereHas('participants', fn ($q) => $q->where('user_id', $targetUserId))
            ->first();

        if (! $room) {
            $room = DB::transaction(function () use ($authId, $targetUserId) {
                $newRoom = ChatRoomModel::create([
                    'name' => null,
                    'is_group' => false,
                ]);

                $newRoom->participants()->createMany([
                    ['user_id' => $authId, 'is_admin' => true, 'last_read_at' => now()],
                    ['user_id' => $targetUserId, 'is_admin' => true, 'last_read_at' => null],
                ]);

                return $newRoom;
            });
        }

        $this->selectedChatRoomId = $room->id;
        $this->markRoomAsRead();

        unset($this->messages, $this->selectedUser, $this->users);
        $this->dispatch('chat-scroll-to-bottom');
    }

    #[Computed]
    public function selectedUser(): ?User
    {
        if (! $this->selectedUserId) {
            return null;
        }

        return User::with(['roles', 'unit'])->find($this->selectedUserId);
    }



    #[Computed]
    public function messages(): Collection
    {
        if (! $this->selectedChatRoomId) {
            return new Collection();
        }

        return ChatMessage::query()
            ->where('chat_room_id', $this->selectedChatRoomId)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function sendMessage(): void
    {
        $trimmed = trim($this->messageText);

        if ($trimmed === '' || ! $this->selectedChatRoomId) {
            return;
        }

        ChatMessage::create([
            'chat_room_id' => $this->selectedChatRoomId,
            'sender_id' => Auth::id(),
            'message' => $trimmed,
        ]);

        $this->markRoomAsRead();
        $this->messageText = '';
        unset($this->messages);

        $this->dispatch('chat-scroll-to-bottom');
    }

    public function pollMessages(): void
    {
        $this->markRoomAsRead();
        unset($this->messages, $this->users);
    }

    protected function markRoomAsRead(): void
    {
        if (! $this->selectedChatRoomId) {
            return;
        }

        ChatRoomParticipant::query()
            ->where('chat_room_id', $this->selectedChatRoomId)
            ->where('user_id', Auth::id())
            ->update(['last_read_at' => now()]);
    }

    public function createGroupAction(): Action
    {
        return Action::make('createGroup')
            ->label(__('chat_room.create_group'))
            ->icon('heroicon-o-user-group')
            ->schema([
                TextInput::make('name')
                    ->label(__('chat_room.group_name'))
                    ->required()
                    ->maxLength(255),
                Select::make('participant_ids')
                    ->label(__('chat_room.select_participants'))
                    ->multiple()
                    ->options(
                        User::query()
                            ->where('id', '!=', Auth::id())
                            ->where('status', UserStatus::ACTIVE)
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
            ])
            ->action(function (array $data): void {
                $room = DB::transaction(function () use ($data) {
                    $newRoom = ChatRoomModel::create([
                        'name' => $data['name'],
                        'is_group' => true,
                    ]);

                    $participants = collect($data['participant_ids'])
                        ->push(Auth::id())
                        ->unique()
                        ->map(fn ($id) => [
                            'user_id' => $id,
                            'is_admin' => $id === Auth::id(),
                            'last_read_at' => $id === Auth::id() ? now() : null,
                        ])
                        ->all();

                    $newRoom->participants()->createMany($participants);

                    return $newRoom;
                });

                $this->selectRoom($room->id);
            });
    }

}