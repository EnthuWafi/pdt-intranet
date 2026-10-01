<?php

namespace App\Filament\Resources\ActionItems\Tables;

use App\Contracts\IsActionable;
use App\Enums\ActionItemStatus;
use App\Enums\ActionType;
use App\Models\ActionItem;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
class ActionItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('assigned_to_user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('assigned_by_user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('actionable_type')
                    ->searchable(),
                TextColumn::make('actionable_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('action_type')
                    ->badge()
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('completed_by_user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                self::getProcessAction(),
                self::getDelegateAction(),
                self::getReturnForRevisionAction(),
                ViewAction::make()
                ])
            ->filters([
                
                // 1. Filter by Task Verb (Approve vs Review)
                SelectFilter::make('action_type')
                    ->label('Action Type')
                    ->options(ActionType::class),

                // 2. Filter by Underlying Entity (ModuleRequest, LeaveRequest, etc.)
                SelectFilter::make('actionable_type')
                    ->label('Target Module')
                    ->options([
                        \App\Models\ModuleRequest::class => 'Module Request',
                        // Register future models here
                    ]),

                // 4. Resolution filter (useful in the History tab)
                SelectFilter::make('status')
                    ->label('Resolution Status')
                    ->options(ActionItemStatus::class),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function getReassignAction(): Action 
    {
        return Action::make('reassign')
            ->label('Reassign')
            ->icon('heroicon-m-arrows-right-left')
            ->color('gray')
            ->visible(fn (ActionItem $record): bool => 
                $record->status === ActionItemStatus::PENDING_APPROVAL &&
                (auth()->user()->hasRole('super_admin') || $record->assigned_by_user_id === auth()->id())
            )
            ->form([
                Select::make('new_assignee_id')
                    ->label('Transfer To')
                    ->options(fn () => User::where('id', '!=', auth()->id())->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Textarea::make('reassignment_reason')
                    ->label('Reason for Reassignment')
                    ->placeholder('e.g., Officer on medical leave; workload rebalancing.')
                    ->required()
                    ->rows(2),
            ])
            ->action(function (ActionItem $record, array $data): void {
                $newAssignee = User::findOrFail($data['new_assignee_id']);

                // Option A: Log to review notes and update assignee directly
                $record->update([
                    'assigned_to_user_id' => $newAssignee->id,
                    'review_notes' => ($record->review_notes ? $record->review_notes . "\n" : '') .
                        "Reassigned to {$newAssignee->name} by " . auth()->user()->name . ". Reason: " . $data['reassignment_reason'],
                ]);

                Notification::make()
                    ->title("Task successfully transferred to {$newAssignee->name}.")
                    ->info()
                    ->send();
            });
    }

    protected static function getProcessAction(): Action
    {
        return Action::make('process')
            ->label(fn (ActionItem $record): string => 
                $record->action_type === ActionType::APPROVE ? 'Approve / Finalize' : 'Submit Review'
            )
            ->icon(fn (ActionItem $record): string => 
                $record->action_type === ActionType::APPROVE ? 'heroicon-m-check-badge' : 'heroicon-m-paper-airplane'
            )
            ->color(fn (ActionItem $record): string => 
                $record->action_type === ActionType::APPROVE ? 'success' : 'primary'
            )
            ->form(function (ActionItem $record): array {
                $domainSchema = $record->actionable instanceof IsActionable
                    ? $record->actionable->getActionFormSchema($record)
                    : [];

                return [
                    Section::make('Request Details')
                        ->label(__('action_inbox.request_details'))
                        ->description(__('action_inbox.fields.field_reminder'))
                        ->schema($domainSchema)
                        ->model($record->actionable),

                    Section::make('Remarks')
                        ->label(__('action_inbox.officer_signoff'))
                        ->schema([
                            Textarea::make('review_notes')
                                ->label(__('action_inbox.fields.review_notes'))
                                ->placeholder(__('action_inbox.fields.note_placeholder'))
                                ->required($record->action_type === ActionType::REVIEW)
                                ->rows(3),
                        ]),
                ];
            })
            ->fillForm(function (ActionItem $record): array {
                $liveValues = $record->actionable?->toArray() ?? [];
                $draftValues = $record->payload ?? [];

                return array_merge($liveValues, $draftValues, [
                    'review_notes' => $record->review_notes,
                ]);
            })
            ->action(function (ActionItem $record, array $data): void {
                $notes = $data['review_notes'] ?? null;
                unset($data['review_notes']);

                if ($record->action_type === ActionType::APPROVE) {
                    $record->approve(auth()->user(), $data, $notes);

                    Notification::make()
                        ->title('Request approved and finalized.')
                        ->success()
                        ->send();
                } else {
                    $record->submitReview(auth()->user(), $data, $notes);

                    Notification::make()
                        ->title('Review submitted to supervisor.')
                        ->info()
                        ->send();
                }
            });
    }

    protected static function getDelegateAction(): Action 
    {
        return Action::make('delegate')
            ->label('Delegate')
            ->icon('heroicon-m-arrow-right-circle')
            ->color('warning')
            ->form([
                Select::make('subordinate_id')
                    ->label('Assign To Subordinate')
                    ->options(fn () => auth()->user()->subordinates()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Textarea::make('instructions')
                    ->label('Delegation Instructions')
                    ->placeholder('Specify what checks or input the subordinate should handle...')
                    ->required(),
            ])
            ->visible(fn (ActionItem $record): bool => 
                $record->status === ActionItemStatus::PENDING_APPROVAL &&
                auth()->user()->subordinates()->exists()
            )
            ->action(function (ActionItem $record, array $data): void {
                $subordinate = User::findOrFail($data['subordinate_id']);
                
                $record->delegate(auth()->user(), $subordinate, $data['instructions']);

                Notification::make()
                    ->title("Task delegated to {$subordinate->name}.")
                    ->warning()
                    ->send();
            });
    }
    /**
     * Action to send work back to the subordinate.
     */
    protected static function getReturnForRevisionAction(): Action
    {
        return Action::make('return_for_revision')
            ->label('Return for Revision')
            ->icon('heroicon-m-arrow-uturn-left')
            ->color('warning')
            ->form([
                Textarea::make('reason')
                    ->label('Revision Instructions')
                    ->placeholder('Specify what errors or discrepancies need fixing...')
                    ->required()
                    ->rows(4),
            ])
            ->visible(fn (ActionItem $record): bool => $record->canBeReturnedForRevision())
            ->action(function (ActionItem $record, array $data): void {
                $record->returnForRevision(auth()->user(), $data['reason']);

                Notification::make()
                    ->title('Task returned to officer for revisions.')
                    ->warning()
                    ->send();
            });
    }
}
