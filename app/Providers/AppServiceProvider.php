<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Drivers\HealthSystemInterface;
use App\Services\Drivers\TasyDriver;
// use App\Services\Drivers\MvDriver; // Futuro
use App\Services\Drivers\LocalDriver;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\Filesystem;
use Masbug\Flysystem\GoogleDriveAdapter;
use Google\Client;
use Google\Service\Drive;

use Spatie\Health\Facades\Health;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Result;
use Spatie\Health\Checks\Check;
use Carbon\Carbon;

use App\Checks\BackupCheck;

use Illuminate\Support\Facades\URL;

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
        // Define quem pode ver o Dashboard de Monitoramento (Laravel Pulse)
        Gate::define('viewPulse', function (User $user) {
            // Apenas Super Admins podem ver a saúde do servidor
            return $user->role === 'super_admin';
        });

        Storage::extend('google', function($app, $config) {
            $client = new \Google\Client();
            $client->setClientId($config['clientId']);
            $client->setClientSecret($config['clientSecret']);
            
            // Define o Refresh Token
            $client->refreshToken($config['refreshToken']);
            
            // FORÇA A GERAÇÃO DO ACCESS TOKEN AGORA
            // Isso garante que o cliente esteja autenticado antes de ser usado
            $newAccessToken = $client->fetchAccessTokenWithRefreshToken($config['refreshToken']);
            $client->setAccessToken($newAccessToken);

            $service = new \Google\Service\Drive($client);
            
            // Pega o ID da pasta do config
            $folderId = $config['folder'] ?? '/';

            // Opções para garantir compatibilidade
            $options = ['useHasDir' => true];
            
            $adapter = new \Masbug\Flysystem\GoogleDriveAdapter($service, $folderId, $options);
            $driver = new \League\Flysystem\Filesystem($adapter);

            return new \Illuminate\Filesystem\FilesystemAdapter($driver, $adapter);
        });

        Health::checks([
            // 1. Monitora o disco da VPS (Alerta em 70%, Erro em 90%)
            UsedDiskSpaceCheck::new()
                ->warnWhenUsedSpaceIsAbovePercentage(70)
                ->failWhenUsedSpaceIsAbovePercentage(90),

            // 2. Garante que o Banco está vivo
            DatabaseCheck::new(),
            
            // 3. Garante que estamos em Produção e Debug desligado
            EnvironmentCheck::new(),
            DebugModeCheck::new(),

            // 4. CHECK PERSONALIZADO DE BACKUP
            // Verifica se existe algum arquivo .zip criado nas últimas 26 horas
            BackupCheck::new(),
                
        ]);

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}