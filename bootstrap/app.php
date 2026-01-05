<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(function (Request $request) {
            // Verifica se a rota atual tem o parâmetro 'tenant_slug'
            $slug = $request->route('tenant_slug');
            
            if ($slug) {
                // Se tem slug, é um paciente tentando acessar o portal.
                // Redireciona para a rota com o NOME CORRETO: 'paciente.login'
                return route('paciente.login', ['tenant_slug' => $slug]);
            }

            // Se não tem slug (acesso genérico), manda para o login do Admin
            return route('filament.admin.auth.login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
