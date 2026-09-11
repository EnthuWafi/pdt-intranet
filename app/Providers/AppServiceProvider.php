<?php

namespace App\Providers;

use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\PermissionRegistrar;
use App\Models\Permission;
use App\Models\Role;

use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use BezhanSalleh\LanguageSwitch\Enums\DisplayMode;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        app(PermissionRegistrar::class)
            ->setPermissionClass(Permission::class)
            ->setRoleClass(Role::class);

        //
        
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch->locales(['en', 'ms'])
            ->displayMode(DisplayMode::Modal)
            ->modalHeading('Choose Language')
            ->modalIcon('heroicon-o-language')
            ->modalIconColor('primary');
        });

        FilamentShield::prohibitDestructiveCommands($this->app->isProduction());
    }
}
