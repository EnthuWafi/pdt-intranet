<?php

namespace App\Filament\Widgets;

use App\Enums\ActionItemStatus;
use App\Models\ActionItem;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ActionItemOverviewWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();

        $myPending = ActionItem::where('assigned_to_user_id', $user->id)
            ->where('status', ActionItemStatus::PENDING_APPROVAL)
            ->count();

        $poolCount = ActionItem::whereNull('assigned_to_user_id')
            ->where('status', ActionItemStatus::PENDING_APPROVAL)
            ->count();

        $completedToday = ActionItem::where('completed_by_user_id', $user->id)
            ->whereDate('completed_at', today())
            ->count();

        return [
            //
            Stat::make('Assigned to My Desk', $myPending)
                ->description('Requires your immediate review/approval')
                ->descriptionIcon('heroicon-m-inbox')
                ->color($myPending > 0 ? 'warning' : 'success'),

            Stat::make('Unassigned Department Pool', $poolCount)
                ->description('Available for claim by qualified officers')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),

            Stat::make('Resolved Today', $completedToday)
                ->description('Completed or delegated tasks')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
}
