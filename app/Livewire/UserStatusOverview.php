<?php

namespace App\Livewire;

use App\Enums\UserStatus;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatusOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('MENUNGGU', User::where('status', UserStatus::PENDING)->count())
                ->description('Permohonan baharu')
                ->icon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('DILULUSKAN', User::where('status', UserStatus::ACTIVE)->count())
                ->description('Akaun aktif')
                ->icon('heroicon-m-check')
                ->color('success'),

            Stat::make('DITOLAK', User::where('status', UserStatus::SUSPENDED)->count())
                ->description('Permohonan ditolak')
                ->icon('heroicon-m-x-mark')
                ->color('danger'),

            Stat::make('JUMLAH PENGGUNA', User::count())
                ->description('Keseluruhan rekod')
                ->icon('heroicon-m-users')
                ->color('gray'),
        ];
    }
}
