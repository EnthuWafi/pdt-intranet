<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Pages\Dashboard;
use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
// use Filament\Pages\Dashboard;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

use Filament\View\PanelsRenderHook;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->colors([
                'primary' => Color::Amber,
                'gray' => Color::Gray,
                'danger'  => Color::Red,
                'warning' => Color::Amber, 
                'success' => Color::Emerald,
                'info'    => Color::Sky,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                NavigationGroup::make()
                    ->label(__('navigation.groups.main'))
                    ->icon('heroicon-o-home')
                    ,

                NavigationGroup::make()
                    ->label(__('navigation.groups.my_work'))
                    ->icon('heroicon-o-clipboard-document-check'),

                NavigationGroup::make()
                    ->label(__('navigation.groups.user_management'))
                    ->icon('heroicon-o-users')
                    ,

                NavigationGroup::make()
                    ->label(__('navigation.groups.system_management'))
                    ->icon('heroicon-o-server-stack')
                    ,

                NavigationGroup::make()
                    ->label(__('navigation.groups.ai_management'))
                    ->icon('heroicon-o-sparkles')
                    ,

                NavigationGroup::make()
                    ->label(__('navigation.groups.parameters'))
                    ->icon('heroicon-o-adjustments-horizontal')
                    ,

                NavigationGroup::make()
                    ->label(__('navigation.groups.report'))
                    ->icon('heroicon-o-document-chart-bar')
                    ,

                NavigationGroup::make()
                    ->label(__('navigation.groups.maintenance'))
                    ->icon('heroicon-o-wrench-screwdriver')
                    ,

                NavigationGroup::make()
                    ->label(__('navigation.groups.setting'))
                    ->icon('heroicon-o-cog-6-tooth')
                    ,

            ])
            ->profile(EditProfile::class)
            ->brandName('Portal Intranet')
            ->brandLogo(fn () => view('filament.components.brand'))
            ->brandLogoHeight('2.5rem')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            // ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\Filament\Clusters')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                PanelsRenderHook::TOPBAR_BEFORE,
                fn () => view('filament.hooks.top-banner')
            )->renderHook(
                PanelsRenderHook::BODY_END,
                fn () => view('filament.hooks.bottom-footer')
            )->renderHook(
                PanelsRenderHook::SIDEBAR_FOOTER,
                fn () => view('filament.hooks.sidebar-footer'),
            );
    }
}
