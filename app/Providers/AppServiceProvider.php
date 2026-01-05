<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\Drivers\HealthSystemInterface;
use App\Services\Drivers\TasyDriver;
// use App\Services\Drivers\MvDriver; // Futuro
use App\Services\Drivers\LocalDriver; // Futuro
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use Filament\Facades\Filament;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(HealthSystemInterface::class, function ($app) {
            
            $tenant = null;

            // 1. TENTATIVA VIA URL (Prioridade para o Portal do Paciente)
            // O request() já estará disponível quando o Controller chamar essa classe
            $slug = request()->route('tenant_slug');
            if ($slug) {
                $tenant = Tenant::where('slug', $slug)->first();
            }

            // 2. TENTATIVA VIA USUÁRIO (Prioridade para Admin/Painel Interno)
            if (!$tenant && Auth::check() && Auth::user()->tenant_id) {
                $tenant = Tenant::find(Auth::user()->tenant_id);
            }

            // 3. FALLBACK (Se não achou nada, cria um vazio para não quebrar erros)
            if (!$tenant) {
                $tenant = new Tenant(); 
            }

            // 4. DECISÃO DO DRIVER (A Mágica acontece aqui)
            if ($tenant->erp_driver === 'tasy') {
                return new TasyDriver($tenant);
            }

            // Padrão: Driver Local
            return new LocalDriver($tenant);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
