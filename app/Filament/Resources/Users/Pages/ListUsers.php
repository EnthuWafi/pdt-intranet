<?php

namespace App\Filament\Resources\Users\Pages;

use App\Enums\UserStatus;
use App\Filament\Resources\Users\UserResource;
use App\Livewire\UserStatusOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            UserStatusOverview::class,
        ];
    }
    public function getDefaultActiveTab(): string | int | null
    {
        return 'all';
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Users'),
            'pending' => Tab::make('Pending Approval')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', UserStatus::PENDING))
                ->badge(fn () => static::getModel()::where('status', UserStatus::PENDING)->count())
                ->badgeColor('warning'),
            'active' => Tab::make('Active')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', UserStatus::ACTIVE)),
            'suspended' => Tab::make('Suspended')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', UserStatus::SUSPENDED)),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
