<?php

namespace App\Providers\Filament;

use App\Models\SiteSetting;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        /*
        |--------------------------------------------------------------------------
        | Brand defaults (SAFE untuk Render / fresh DB)
        |--------------------------------------------------------------------------
        */
        $brandName = config('app.name', 'Portfolio');
        $faviconUrl = null;
        $brandLogoUrl = null;

        // Jangan crash kalau DB belum migrate
        try {
            if (Schema::hasTable('site_settings')) {
                $settings = SiteSetting::instance();

                if (! empty($settings->brand_name)) {
                    $brandName = $settings->brand_name;
                }

                if (! empty($settings->favicon_path)) {
                    $faviconUrl = asset('storage/' . ltrim($settings->favicon_path, '/'));
                    $brandLogoUrl = $faviconUrl;
                }
            }
        } catch (\Throwable $e) {
            // Ignore — fallback to defaults
        }

        /*
        |--------------------------------------------------------------------------
        | Panel config
        |--------------------------------------------------------------------------
        */
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')

            // 🔴 WAJIB untuk elak login loop
            ->login()
            ->authGuard('web')

            ->brandName($brandName)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->favicon($faviconUrl)
            ->brandLogo($brandLogoUrl)
            ->brandLogoHeight('3rem')

            ->discoverResources(
                in: app_path('Filament/Resources'),
                for: 'App\\Filament\\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Pages'),
                for: 'App\\Filament\\Pages'
            )
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(
                in: app_path('Filament/Widgets'),
                for: 'App\\Filament\\Widgets'
            )
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])

            /*
            |--------------------------------------------------------------------------
            | Middleware (Render-safe)
            |--------------------------------------------------------------------------
            */
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])

            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
