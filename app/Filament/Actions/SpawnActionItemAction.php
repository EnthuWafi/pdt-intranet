<?php

namespace App\Filament\Actions;

use App\Contracts\IsActionable;
use App\Enums\ActionItemStatus;
use App\Enums\ActionType;
use App\Filament\Schemas\Components\PermissionRuleBuilder;
use App\Models\ActionItem;
use App\Models\User;
use App\Notifications\TaskDelegatedNotification;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class SpawnActionItemAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'spawn_action_item';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('action_inbox.spawn.title'))
            ->icon('heroicon-m-user-plus')
            ->color('warning')
            ->form(function (Model $record): array {
                $user = auth()->user();
                $hasSubordinates = $user->subordinates()->exists();

                return [
                    TextInput::make('title')
                        ->label(__('action_inbox.spawn.fields.title'))
                        ->placeholder(__('action_inbox.spawn.fields.title_placeholder'))
                        ->required()
                        ->maxLength(255),

                    Radio::make('assignment_type')
                        ->label(__('action_inbox.spawn.fields.assignment_type'))
                        ->options([
                            'pool' => __('action_inbox.spawn.fields.pool'),
                            'subordinate' => __('action_inbox.spawn.fields.subordinate'),
                        ])
                        ->default($hasSubordinates ? 'subordinate' : 'pool')
                        ->live(),

                    Select::make('assigned_to_user_id')
                        ->label(__('action_inbox.spawn.fields.assigned_to_user_id'))
                        ->options(fn () => $user->subordinates()->pluck('name', 'id'))
                        ->searchable()
                        ->required()
                        ->visible(fn (Get $get) => $get('assignment_type') === 'subordinate'),

                    Textarea::make('instructions')
                        ->label(__('action_inbox.spawn.fields.instructions'))
                        ->placeholder(__('action_inbox.spawn.fields.instructions_placeholder'))
                        ->required()
                        ->rows(3),

                    PermissionRuleBuilder::make('required_permissions')
                        ->label(__('action_inbox.spawn.fields.required_permissions'))
                        ->forActionable()
                        ->visible(fn (Get $get): bool => $get('assignment_type') === 'pool')
                ];
            })
            ->action(function (Model $record, array $data): void {

                $isPool = $data['assignment_type'] === 'pool';
                
                $assigneeId = $isPool ? null : $data['assigned_to_user_id'];

                $assignee = User::find($assigneeId) ?? null;

                if ($assignee == null) {
                    Notification::make()
                    ->title(__('user.notification.does_not_exist'))
                    ->danger()
                    ->send();
                    return;
                }

                ActionItem::create([
                    'actionable_type'      => $record->getMorphClass(),
                    'actionable_id'        => $record->getKey(),
                    'assigned_by_user_id'  => auth()->id(),
                    'assigned_to_user_id'  => $assigneeId,
                    'title'                => $data['title'],
                    'description'          => $data['instructions'],
                    'action_type'          => ActionType::REVIEW,
                    'status'               => ActionItemStatus::PENDING_APPROVAL,
                    'required_permissions' => $isPool ? ($data['required_permissions'] ?? []) : [],
                ]);

                //notify
                if ($assigneeId != null) {
                    $assignee->notify(new TaskDelegatedNotification($data['title'], $assignee->name));
                }

                // for user
                $recipientNotice = $assigneeId
                    ? __('action_inbox.notifications.assigned')
                    : __('action_inbox.notifications.unassigned');

                Notification::make()
                    ->title($recipientNotice)
                    ->success()
                    ->send();
            });
    }
}