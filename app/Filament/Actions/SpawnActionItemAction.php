<?php

namespace App\Filament\Actions;

use App\Contracts\IsActionable;
use App\Enums\ActionItemStatus;
use App\Enums\ActionType;
use App\Filament\Schemas\Components\PermissionRuleBuilder;
use App\Models\ActionItem;
use App\Models\User;
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
            ->label('Request Action / Delegate')
            ->icon('heroicon-m-user-plus')
            ->color('warning')
            ->form(function (Model $record): array {
                $user = auth()->user();
                $hasSubordinates = $user->subordinates()->exists();

                return [
                    TextInput::make('title')
                        ->label('Task Summary')
                        ->placeholder('e.g., Verify quota allocation, Check server logs')
                        ->required()
                        ->maxLength(255),

                    Radio::make('assignment_type')
                        ->label('Assignment Target')
                        ->options([
                            'pool' => 'Shared Department Pool (Anyone can pick up)',
                            'subordinate' => 'Assign to Specific Subordinate',
                        ])
                        ->default($hasSubordinates ? 'subordinate' : 'pool')
                        ->live(),

                    Select::make('assigned_to_user_id')
                        ->label('Select Subordinate')
                        ->options(fn () => $user->subordinates()->pluck('name', 'id'))
                        ->searchable()
                        ->required()
                        ->visible(fn (Get $get) => $get('assignment_type') === 'subordinate'),

                    Textarea::make('instructions')
                        ->label('Instructions / Notes')
                        ->placeholder('Describe what needs to be verified or updated...')
                        ->required()
                        ->rows(3),

                    PermissionRuleBuilder::make('required_permissions')
                        ->forActionable()
                        ->visible(fn (Get $get): bool => $get('assignment_type') === 'pool')
                ];
            })
            ->action(function (Model $record, array $data): void {
                $assigneeId = ($data['assignment_type'] === 'subordinate')
                    ? $data['assigned_to_user_id']
                    : null; // null represents the shared pool

                // Pull required permissions from model contract if available
                $permissions = ($record instanceof IsActionable)
                    ? $record->getRequiredPermissionsFor(ActionType::REVIEW)
                    : [];

                ActionItem::create([
                    'actionable_type'      => $record->getMorphClass(),
                    'actionable_id'        => $record->getKey(),
                    'assigned_by_user_id'  => auth()->id(),
                    'assigned_to_user_id'  => $assigneeId,
                    'title'                => $data['title'],
                    'description'          => $data['instructions'],
                    'action_type'          => ActionType::REVIEW,
                    'status'               => ActionItemStatus::PENDING_APPROVAL,
                    'required_permissions' => $permissions,
                ]);

                $recipientNotice = $assigneeId
                    ? 'Task assigned to subordinate.'
                    : 'Task sent to shared department pool.';

                Notification::make()
                    ->title($recipientNotice)
                    ->success()
                    ->send();
            });
    }
}