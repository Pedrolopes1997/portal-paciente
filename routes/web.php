<?php

use App\Http\Controllers\Auth\PacienteLoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExameController; // <--- Importante estar aqui
use Illuminate\Support\Facades\Route;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;
use App\Livewire\Patient\NewAppointment;


Route::get('/health', HealthCheckResultsController::class); // Rota para o painel de saúde do sistema

// Rota da Landing Page (Página de Vendas)
Route::get('/', function () {
    return view('landing');
})->name('home');

// Rotas de redirecionamento de login (Opcional, caso o Filament precise)
Route::get('/login', function () {
    return redirect()->route('filament.painel.auth.login');
})->name('login');

// Rota de Emergência para deslogar de qualquer lugar e limpar sessão
Route::any('/sair-geral', function () {
    \Illuminate\Support\Facades\Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    
    // Redireciona para o login do Admin limpo
    return redirect('/admin/login');
})->name('logout.emergencia');

// --- PORTAL DO PACIENTE ---
// Mantemos o prefix simples para garantir que funciona
Route::prefix('{tenant_slug}')
    ->middleware([\App\Http\Middleware\ConfigurarTenant::class])
    ->group(function () {

        // Login & Logout
        Route::get('/login', [PacienteLoginController::class, 'showLoginForm'])->name('paciente.login');
        Route::post('/login', [PacienteLoginController::class, 'login'])->name('paciente.login.post');
        Route::post('/logout', [PacienteLoginController::class, 'logout'])->name('paciente.logout');

        // Primeiro Acesso
        Route::get('/primeiro-acesso', [App\Http\Controllers\Auth\FirstAccessController::class, 'create'])->name('paciente.primeiro-acesso');
        Route::post('/primeiro-acesso', [App\Http\Controllers\Auth\FirstAccessController::class, 'store'])->name('paciente.primeiro-acesso.store');

        // ROTAS DE RECUPERAÇÃO DE SENHA
        Route::get('/esqueci-senha', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])
            ->name('paciente.password.request');

        Route::post('/esqueci-senha', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])
            ->name('paciente.password.email');

        Route::get('/nova-senha/{token}', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetForm'])
            ->name('paciente.password.reset');

        Route::post('/nova-senha', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'reset'])
            ->name('paciente.password.update');

        Route::get('/termos-de-uso', function () {
            return view('auth.termos');
        })->name('paciente.termos');

        // Área Logada
        Route::middleware('auth')->group(function () {
            
            // Dashboard (Home / Resumo)
            Route::get('/', [DashboardController::class, 'index'])->name('paciente.dashboard');

            // Rota para a tela de Novo Agendamento
            Route::get('/agendar', \App\Livewire\Patient\NewAppointment::class)->name('paciente.agendar');
            
            // Novas Páginas
            Route::get('/minha-agenda', [DashboardController::class, 'agendamentos'])->name('paciente.agenda');
            Route::get('/meus-exames', [DashboardController::class, 'exames'])->name('paciente.exames');

            // Funcionalidades (PDF e Cancelar)
            Route::get('/exames/{id}/visualizar', [ExameController::class, 'visualizar'])->name('paciente.exames.visualizar');
            Route::post('/agendamentos/{id}/cancelar', [DashboardController::class, 'cancelarAgendamento'])->name('paciente.agendamentos.cancelar');

            // ROTA DE PERFIL
            Route::get('/meu-perfil', [App\Http\Controllers\ProfileController::class, 'edit'])->name('paciente.profile');
            Route::put('/meu-perfil', [App\Http\Controllers\ProfileController::class, 'update'])->name('paciente.profile.update');
        });

        
        // Coloque isso na ÚLTIMA LINHA do arquivo, fora de qualquer grupo
        Route::get('/imprimir/agenda/{tenant}', function ($tenantSlug) {
            
            if (!auth()->check()) {
                return redirect('/painel/login');
            }
            
            $tenant = \App\Models\Tenant::where('slug', $tenantSlug)->firstOrFail();
            
            $agendamentos = \App\Models\Appointment::where('tenant_id', $tenant->id)
                ->whereDate('scheduled_at', now())
                ->orderBy('scheduled_at')
                ->get();

            return view('painel.print-agenda', compact('agendamentos', 'tenant'));

        })->name('painel.imprimir.agenda');
    });