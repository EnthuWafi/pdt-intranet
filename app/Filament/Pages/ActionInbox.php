<?php

// namespace App\Filament\Pages;

// use App\Contracts\IsActionable;
// use App\Enums\ActionItemStatus;
// use App\Enums\ActionType;
// use App\Enums\ActivityEvent;
// use App\Models\ActionItem;
// use App\Models\ActivityLog;
// use App\Models\User;
// use BackedEnum;
// use Filament\Actions\Action;
// use Filament\Forms\Components\Select;
// use Filament\Forms\Components\Textarea;
// use Filament\Notifications\Notification;
// use Filament\Pages\Page;
// use Filament\Schemas\Components\Section;
// use Filament\Tables\Columns\TextColumn;
// use Filament\Tables\Concerns\InteractsWithTable;
// use Filament\Tables\Contracts\HasTable;
// use Filament\Tables\Filters\SelectFilter;
// use Filament\Tables\Filters\TernaryFilter;
// use Filament\Tables\Table;
// use Illuminate\Database\Eloquent\Builder;

// class ActionInbox extends Page implements HasTable
// {
//     use InteractsWithTable;

//     protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-inbox-stack';
//     protected static ?int $navigationSort = 1;
//     protected string $view = 'filament.pages.action-inbox';

//     public static function getNavigationGroup(): ?string
//     {
//         return __('navigation.groups.my_work');
//     }

//     public static function getNavigationLabel(): string
//     {
//         return __('action_inbox.title');
//     }

//     public function getTitle(): string
//     {
//         return __('action_inbox.title');
//     }

//     public static function canAccess(): bool
//     {
//         return auth()->check();
//     }

//     public function table(Table $table): Table
//     {
//         return $table
//             ->query(ActionItem::query()->forUser(auth()->user())->latest())
//             ->emptyStateHeading(__('action_inbox.empty'))
//             ->columns([
//                 TextColumn::make('title')
//                 ->label(__('action_inbox.fields.title'))
//                 ->weight('bold')
//                 ->searchable(),

//                 TextColumn::make('action_type')
//                     ->label(__('action_inbox.fields.type'))
//                     ->badge(),

//                 TextColumn::make('assignedTo.name')
//                     ->label(__('action_inbox.fields.assigned_to'))
//                     ->placeholder(__('action_inbox.status.unassigned')),

//                 TextColumn::make('created_at')
//                     ->label(__('action_inbox.fields.received_at'))
//                     ->dateTime('d M Y, h:i A')
//                     ->sortable(),
//                 ])
//             ->actions([
//                 $this->getProcessAction(),
//                 $this->getReturnForRevisionAction(),
//                 ])
//             ->filters([
//                 //Filter by Status
//                 SelectFilter::make('status')
//                     ->label(__('action_inbox.fields.status'))
//                     ->options(ActionItemStatus::class)
//                     ->default(ActionItemStatus::PENDING_APPROVAL->value),
//                 // Filter by Action Type (Approval vs Review)
//                 SelectFilter::make('action_type')
//                     ->label('Action Type')
//                     ->options(ActionType::class),

//                 // Filter to isolate tasks returned with revision notes
//                 TernaryFilter::make('is_revision')
//                     ->label('Returned for Revision')
//                     ->placeholder('All items')
//                     ->trueLabel('Revisions only')
//                     ->falseLabel('First-time submissions only')
//                     ->queries(
//                         true: fn (Builder $query) => $query->whereNotNull('review_notes'),
//                         false: fn (Builder $query) => $query->whereNull('review_notes'),
//                     ),

//                 // Filter to view unassigned pool tasks vs assigned
//                 TernaryFilter::make('pooled')
//                     ->label('Pool Status')
//                     ->placeholder('All tasks')
//                     ->trueLabel('Unassigned (Pool)')
//                     ->falseLabel('Assigned to me')
//                     ->queries(
//                         true: fn (Builder $query) => $query->whereNull('assigned_to_user_id'),
//                         false: fn (Builder $query) => $query->where('assigned_to_user_id', auth()->id()),
//                     ),
//             ]);
//     }
//     /**
//      * Unified Process Action: Dynamically branches into approve() or submitReview().
//      */
//     protected function getProcessAction(): Action
//     {
//         return Action::make('process')
//             ->label(fn (ActionItem $record): string => 
//                 $record->action_type === ActionType::APPROVE ? 'Approve / Finalize' : 'Submit Review'
//             )
//             ->icon(fn (ActionItem $record): string => 
//                 $record->action_type === ActionType::APPROVE ? 'heroicon-m-check-badge' : 'heroicon-m-paper-airplane'
//             )
//             ->color(fn (ActionItem $record): string => 
//                 $record->action_type === ActionType::APPROVE ? 'success' : 'primary'
//             )
//             ->form(function (ActionItem $record): array {
//                 $domainSchema = $record->actionable instanceof IsActionable
//                     ? $record->actionable->getActionFormSchema($record)
//                     : [];

//                 return [
//                     Section::make('Request Details')
//                         ->label(__('action_inbox.request_details'))
//                         ->description(__('action_inbox.fields.field_reminder'))
//                         ->schema($domainSchema)
//                         ->model($record->actionable),

//                     Section::make('Remarks')
//                         ->label(__('action_inbox.officer_signoff'))
//                         ->schema([
//                             Textarea::make('review_notes')
//                                 ->label(__('action_inbox.fields.review_notes'))
//                                 ->placeholder(__('action_inbox.fields.note_placeholder'))
//                                 ->required($record->action_type === ActionType::REVIEW)
//                                 ->rows(3),
//                         ]),
//                 ];
//             })
//             ->fillForm(function (ActionItem $record): array {
//                 $liveValues = $record->actionable?->toArray() ?? [];
//                 $draftValues = $record->payload ?? [];

//                 return array_merge($liveValues, $draftValues, [
//                     'review_notes' => $record->review_notes,
//                 ]);
//             })
//             ->action(function (ActionItem $record, array $data): void {
//                 $notes = $data['review_notes'] ?? null;
//                 unset($data['review_notes']);

//                 if ($record->action_type === ActionType::APPROVE) {
//                     $record->approve(auth()->user(), $data, $notes);

//                     Notification::make()
//                         ->title('Request approved and finalized.')
//                         ->success()
//                         ->send();
//                 } else {
//                     $record->submitReview(auth()->user(), $data, $notes);

//                     Notification::make()
//                         ->title('Review submitted to supervisor.')
//                         ->info()
//                         ->send();
//                 }
//             });
//     }

//     /**
//      * Action to send work back to the subordinate.
//      */
//     protected function getReturnForRevisionAction(): Action
//     {
//         return Action::make('return_for_revision')
//             ->label('Return for Revision')
//             ->icon('heroicon-m-arrow-uturn-left')
//             ->color('warning')
//             ->form([
//                 Textarea::make('reason')
//                     ->label('Revision Instructions')
//                     ->placeholder('Specify what errors or discrepancies need fixing...')
//                     ->required()
//                     ->rows(4),
//             ])
//             ->visible(fn (ActionItem $record): bool => $record->canBeReturnedForRevision())
//             ->action(function (ActionItem $record, array $data): void {
//                 $record->returnForRevision(auth()->user(), $data['reason']);

//                 Notification::make()
//                     ->title('Task returned to officer for revisions.')
//                     ->warning()
//                     ->send();
//             });
//     }
    
// }