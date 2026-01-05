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

        // --- NOVO CÓDIGO: BLOQUEIO ADMINISTRATIVO ---
        if (!$tenant->is_active) {
            abort(403, 'ESTA CLÍNICA ESTÁ TEMPORARIAMENTE SUSPENSA. ENTRE EM CONTATO COM O SUPORTE.');
        }

        // 3. Salva o tenant na memória global do Laravel (para usarmos nos Controllers/Views)
        // Isso permite acessarmos app('currentTenant') em qualquer lugar
        app()->instance('currentTenant', $tenant);

        // 4. Define valores padrões para URL (para os links route() funcionarem sozinhos)
        URL::defaults(['tenant_slug' => $slug]);

        // 5. Compartilha com todas as Views (para colocar o Logo e Nome da clinica no login)
        View::share('currentTenant', $tenant);

        // 6. Configura o Banco de Dados (Igual fizemos antes)
        if ($tenant->mode === 'integrated' && !empty($tenant->db_connection_data)) {
            $dados = $tenant->db_connection_data;
            
            // ATENÇÃO: Verifique se os nomes das chaves batem com o que salvamos no admin
            // (db_host ou host, etc). O código abaixo assume que salvamos certo.
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