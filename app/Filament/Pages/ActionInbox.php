<?php

namespace App\Filament\Pages;

use App\Enums\ActionItemStatus;
use App\Enums\ActivityEvent;
use App\Models\ActionItem;
use App\Models\ActivityLog;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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

    public function table(Table $table): Table
    {
        return $table
            ->query(ActionItem::query()->forUser(auth()->user()))
            ->emptyStateHeading(__('action_inbox.empty'))
            ->columns([
                TextColumn::make('title')
                ->label(__('action_inbox.fields.title'))
                ->weight('bold')
                ->searchable(),

                TextColumn::make('action_type')
                    ->label(__('action_inbox.fields.type'))
                    ->badge(),

                TextColumn::make('assignedTo.name')
                    ->label(__('action_inbox.fields.assigned_to'))
                    ->placeholder(__('action_inbox.status.unassigned')),

                TextColumn::make('created_at')
                    ->label(__('action_inbox.fields.received_at'))
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
                ])
            ->actions([
                        
            ]);
    }
}