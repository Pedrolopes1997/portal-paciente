<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class ConfigurarTenant
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Pega o slug da URL (ex: 'hospital-vida')
        $slug = $request->route('tenant_slug');

        if (!$slug) {
            return $next($request);
        }

        // 2. Busca o Tenant no banco
        $tenant = Tenant::where('slug', $slug)->firstOrFail();

        // --- BLOQUEIO ADMINISTRATIVO ---
        if (!$tenant->is_active) {
            abort(403, 'ESTA CLÍNICA ESTÁ TEMPORARIAMENTE SUSPENSA. ENTRE EM CONTATO COM O SUPORTE.');
        }

        // 3. Salva o tenant na memória global do Laravel
        app()->instance('currentTenant', $tenant);

        // 4. Define valores padrões para URL
        URL::defaults(['tenant_slug' => $slug]);

        // 5. Compartilha com todas as Views
        View::share('currentTenant', $tenant);

        // --- NOVO: CONFIGURAÇÃO WHITE LABEL (E-mail e Nome do App) ---
        // Pega o nome do hospital (ajuste 'name' se sua coluna for 'nome_fantasia')
        $nomeHospital = $tenant->name ?? $tenant->nome_fantasia ?? 'WeCare';
        
        // Define que os e-mails sairão com o nome do Hospital
        Config::set('mail.from.name', $nomeHospital);
        // Define o nome do App (aparece no rodapé dos e-mails e notificações)
        Config::set('app.name', $nomeHospital);

        // 6. Configura o Banco de Dados (Tasy/Oracle)
        if ($tenant->mode === 'integrated' && !empty($tenant->db_connection_data)) {
            $dados = $tenant->db_connection_data;
            
            Config::set('database.connections.tenant_erp', [
                'driver'   => 'oracle',
                'host'     => $dados['db_host'] ?? '',
                'port'     => $dados['db_port'] ?? '1521',
                'database' => $dados['db_database'] ?? '',
                'username' => $dados['db_username'] ?? '',
                'password' => $dados['db_password'] ?? '',
                'charset'  => 'WE8MSWIN1252',
                'prefix'   => '',
            ]);

            DB::purge('tenant_erp');
        }

        return $next($request);
    }
}