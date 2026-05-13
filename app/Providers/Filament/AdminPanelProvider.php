<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Pages\AnalyticsDashboard;
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
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')

            // ── Branding ────────────────────────────────
            ->brandName('CSM Admin')
            ->brandLogo('https://sdolegazpicity.com/wp-content/uploads/2025/12/cropped-LOGO-sdo-leg-1-1.png')
            ->brandLogoHeight('2.5rem')
            ->favicon('https://sdolegazpicity.com/wp-content/uploads/2025/12/cropped-LOGO-sdo-leg-1-1.png')

            // ── Login ────────────────────────────────────
            ->login()
            
            // ── Colors ───────────────────────────────────
            ->colors([
                'primary' => Color::Teal,
                'danger'  => Color::Rose,
                'info'    => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])

            // ── Navigation ───────────────────────────────
            ->navigationGroups([
                'Survey Setup',
                'Survey Results',
                'System',
            ])

            // ── Sidebar collapsed on mobile ───────────────
            ->sidebarCollapsibleOnDesktop()

            // ── Resources, Pages & Widgets ───────────────────────────────
            ->discoverResources
                (in: app_path('Filament/Resources'), 
                for: 'App\Filament\Resources')

            ->discoverPages
                (in: app_path('Filament/Pages'), 
                for: 'App\Filament\Pages')

            ->pages([
                AnalyticsDashboard::class,
            ])

            ->discoverWidgets
                (in: app_path('Filament/Widgets'), 
                for: 'App\Filament\Widgets')

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
            
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
