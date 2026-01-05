<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Models\Tenant;
use App\Filament\App\Widgets\StatsOverview;
use Filament\Facades\Filament; 
use Illuminate\Support\HtmlString; 

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('painel')
            ->path('painel') 
            ->login()
            
            // --- MELHORIAS VISUAIS ---
            ->spa() // Navegação instantânea (parece app nativo)
            ->font('DM Sans') // Fonte moderna e limpa
            ->colors([
                'primary' => Color::Sky, // Azul mais suave/hospitalar que o Indigo
                'gray' => Color::Slate,  // Cinza azulado, mais elegante
            ])
            ->sidebarCollapsibleOnDesktop() // Permite recolher o menu para focar nos dados
            ->maxContentWidth('full') // Usa toda a largura da tela
            // -------------------------

            // Lógica da Marca (Mantida)
            ->brandName(fn () => Filament::getTenant()?->name ?? 'Portal do Paciente')
            ->brandLogo(function () {
                $tenant = Filament::getTenant();
                if ($tenant && $tenant->logo_path) {
                    $url = asset('storage/' . $tenant->logo_path);
                    return new HtmlString("<img src='{$url}' alt='{$tenant->name}' style='height: 2.5rem; width: auto;' />"); 
                }
                return null;
            })
            ->brandLogoHeight('3rem')

            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            
            ->widgets([
                StatsOverview::class,
            ])

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
            ])
            ->tenant(Tenant::class, slugAttribute: 'slug');
    }
}