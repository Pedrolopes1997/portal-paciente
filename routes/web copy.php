<?php

use App\Http\Controllers\Auth\PacienteLoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExameController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aqui registramos as rotas do Portal do Paciente.
| As rotas do ADMIN (/admin) e do PAINEL DA CLÍNICA (/painel) 
| são carregadas automaticamente pelo Filament, não mexemos nelas aqui.
|
*/

// Rota Raiz: Redireciona para o login administrativo
Route::get('/', function () {
    return redirect('/admin');
});

// --- PORTAL DO PACIENTE ---
// A Regex abaixo (where) é a GUARDIÃ. Ela impede que o Laravel confunda
// '/admin' ou '/painel' com o nome de uma clínica.
Route::prefix('{tenant_slug}')
    ->where(['tenant_slug' => '^(?!admin|painel|livewire|filament|storage|_debugbar).*$'])
    ->middleware([\App\Http\Middleware\ConfigurarTenant::class])
    ->group(function () {

        // 1. Rota de Login (GET)
        Route::get('/login', [PacienteLoginController::class, 'showLoginForm'])
            ->name('paciente.login');
        
        // 2. Rota de Processar Login (POST)
        Route::post('/login', [PacienteLoginController::class, 'login'])
            ->name('paciente.login.post');

        // 3. Rota de Logout (POST)
        Route::post('/logout', [PacienteLoginController::class, 'logout'])
            ->name('paciente.logout');

        // 4. Área Logada do Paciente
        Route::middleware('auth')->group(function () {
            
            // Dashboard (Home)
            Route::get('/', [DashboardController::class, 'index'])
                ->name('paciente.dashboard');
            
            // Visualizar Exame (PDF)
            // Certifique-se que o ExameController existe antes de descomentar
            Route::get('/exames/{id}/visualizar', function ($tenant_slug, $id) {
                return "CHEGUEI AQUI! <br> Tenant: $tenant_slug <br> ID do Exame: $id";
            })->name('paciente.exames.visualizar');

        });
    });