$this->app->bind(HealthSystemInterface::class, function ($app) {
    // 1. Pega a clínica atual (gerenciada pelo Middleware)
    $tenant = \App\Models\Tenant::current(); 
    
    // Fallback: Se não tiver tenant detectado (ex: rodando no console), evita erro
    $tenantId = $tenant ? $tenant->id : null;

    // Lógica de escolha
    if ($tenant && $tenant->erp_driver === 'tasy') {
         return new TasyDriver($tenantId); // ou sem ID, dependendo da sua lógica Oracle
    }

    // AQUI É O PULO DO GATO: Tem que passar o ID para o LocalDriver
    return new LocalDriver($tenantId);
});