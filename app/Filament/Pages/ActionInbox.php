<?php

namespace App\Filament\Pages;

use App\Enums\ActivityEvent;
use App\Enums\ModuleStatus;
use App\Models\ActivityLog;
use App\Models\ModuleRequest;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class ActionInbox extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-inbox-stack';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.pages.action-inbox';

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.my_work');
    }

    public static function getNavigationLabel(): string
    {
        return __('action_inbox.title');
    }

    public function getTitle(): string
    {
        return __('action_inbox.title');
    }

    public static function canAccess(): bool
    {
        return auth()->check();
    }

    // public static function canAccess(): bool
    // {
    //     return auth()->user()?->can('viewAny', ModuleRequest::class) ?? false;
    // }

    // public static function getNavigationBadge(): ?string
    // {
    //     $count = ModuleRequest::where('status', ModuleStatus::PENDING)->count();

    //     return $count > 0 ? (string) $count : null;
    // }

    // public static function getNavigationBadgeColor(): ?string
    // {
    //     return 'danger';
    // }

    // public function table(Table $table): Table
    // {
    //     return $table
    //         ->query(
    //             ModuleRequest::query()
    //                 ->with(['user', 'module'])
    //                 ->where('status', ModuleStatus::PENDING)
    //         )
    //         ->emptyStateHeading(__('portal.empty.action_inbox'))
    //         ->columns([
    //             TextColumn::make('user.name')
    //                 ->label(__('portal.fields.applicant'))
    //                 ->searchable()
    //                 ->sortable(),

    //             TextColumn::make('module.name')
    //                 ->label(__('portal.fields.module'))
    //                 ->badge()
    //                 ->color('warning'),

    //             TextColumn::make('purpose')
    //                 ->label(__('portal.fields.purpose'))
    //                 ->limit(50)
    //                 ->tooltip(fn ($record) => $record->purpose),

    //             TextColumn::make('created_at')
    //                 ->label(__('portal.fields.requested_at'))
    //                 ->dateTime('d M Y, h:i A')
    //                 ->sortable(),
    //         ])
    //         ->actions([
    //             Action::make('approve')
    //                 ->label(__('portal.actions.approve'))
    //                 ->icon('heroicon-m-check')
    //                 ->color('success')
    //                 ->requiresConfirmation()
    //                 ->authorize(fn (ModuleRequest $record) => auth()->user()->can('approve', $record))
    //                 ->action(function (ModuleRequest $record): void {
    //                     $record->update(['status' => ModuleStatus::APPROVED]);

    //                     ActivityLog::log(
    //                         event: ActivityEvent::APPROVED,
    //                         description: __('portal.notifications.log_approved', [
    //                             'module' => $record->module->name,
    //                             'user' => $record->user->name,
    //                         ]),
    //                         subject: $record
    //                     );

    //                     Notification::make()
    //                         ->title(__('portal.notifications.approved_title'))
    //                         ->success()
    //                         ->send();
    //                 }),

    //             Action::make('reject')
    //                 ->label(__('portal.actions.reject'))
    //                 ->icon('heroicon-m-x-mark')
    //                 ->color('danger')
    //                 ->requiresConfirmation()
    //                 ->authorize(fn (ModuleRequest $record) => auth()->user()->can('reject', $record))
    //                 ->action(function (ModuleRequest $record): void {
    //                     $record->update(['status' => ModuleStatus::REJECTED]);

    //                     ActivityLog::log(
    //                         event: ActivityEvent::REJECTED,
    //                         description: __('portal.notifications.log_rejected', [
    //                             'module' => $record->module->name,
    //                             'user' => $record->user->name,
    //                         ]),
    //                         subject: $record
    //                     );

    //                     Notification::make()
    //                         ->title(__('portal.notifications.rejected_title'))
    //                         ->danger()
    //                         ->send();
    //                 }),
    //         ]);
    // }
}