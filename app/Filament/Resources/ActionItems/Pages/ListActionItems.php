<?php

namespace App\Filament\Resources\ActionItems\Pages;

use App\Enums\ActionItemStatus;
use App\Filament\Resources\ActionItems\ActionItemResource;
use App\Models\ActionItem;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Builder;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListActionItems extends ListRecords
{
    protected static string $resource = ActionItemResource::class;

    public function getDefaultActiveTab(): string | int | null
    {
        return 'my_tasks';
    }

    public function getTabs(): array
    {
        $userId = auth()->id();

        return [
            // 1. Items on the user's desk right now
            'my_tasks' => Tab::make('My Tasks')
                ->badge(fn () => ActionItem::query()
                    ->where('assigned_to_user_id', $userId)
                    ->where('status', ActionItemStatus::PENDING_APPROVAL)
                    ->count()
                )
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->where('assigned_to_user_id', $userId)
                          ->where('status', ActionItemStatus::PENDING_APPROVAL)
                ),

            // 2. Unassigned items in the shared department pool
            'pool' => Tab::make('Shared Pool')
                ->badge(fn () => ActionItem::forUser(auth()->user())
                    ->whereNull('assigned_to_user_id')
                    ->where('status', ActionItemStatus::PENDING_APPROVAL)
                    ->count()
                )
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->whereNull('assigned_to_user_id')
                          ->where('status', ActionItemStatus::PENDING_APPROVAL)
                ),

            // 3. Work delegated out to subordinates still in progress
            'delegated' => Tab::make('Delegated by Me')
                ->badge(fn () => ActionItem::query()
                    ->where('assigned_by_user_id', $userId)
                    ->where('assigned_to_user_id', '!=', $userId)
                    ->where('status', ActionItemStatus::PENDING_APPROVAL)
                    ->count()
                )
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->where('assigned_by_user_id', $userId)
                          ->where('assigned_to_user_id', '!=', $userId)
                          ->where('status', ActionItemStatus::PENDING_APPROVAL)
                ),

            // 4. Closed tasks: includes both COMPLETED and CANCELLED
            'history' => Tab::make('History / Closed')
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->whereIn('status', [
                        ActionItemStatus::COMPLETED,
                        ActionItemStatus::CANCELLED,
                    ])
                ),

        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
