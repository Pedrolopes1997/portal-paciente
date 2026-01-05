<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Tasy\TasyPacienteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Tenant;

class PacienteLoginController extends Controller
{
    protected $tasyService;

    public function __construct(TasyPacienteService $tasyService)
    {
        $this->tasyService = $tasyService;
    }

    public function showLoginForm()
    {
        // Pega o tenant que foi injetado pelo Middleware na Request
        // (Ou pega via view share se vc configurou assim)
        $tenant = request()->get('tenant'); 
        
        // Fallback caso o middleware não tenha injetado na request direto
        if (!$tenant) {
            $slug = request()->route('tenant_slug');
            $tenant = Tenant::where('slug', $slug)->firstOrFail();
        }

        return view('paciente.login', compact('tenant'));
    }

    public function login(Request $request)
    {
        // 1. Validação simples
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Identificar a Clínica Atual (Segurança Crítica)
        $slug = $request->route('tenant_slug');
        $tenant = Tenant::where('slug', $slug)->firstOrFail();

        // 3. Tentar logar usando o Guard Web (Sessão Padrão)
        // O array $credentials já tem email e password.
        // Adicionamos a verificação de 'tenant_id' para garantir 
        // que o usuário pertença a ESTA clínica.
        
        // Verifica primeiro se o usuário existe e pertence ao tenant
        $user = User::where('email', $credentials['email'])
                    ->where('tenant_id', $tenant->id)
                    ->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Usuário não encontrado nesta clínica.',
            ]);
        }

        // Tenta autenticar a senha
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'tenant_id' => $tenant->id])) {
            $request->session()->regenerate();

            return redirect()->route('paciente.dashboard', ['tenant_slug' => $tenant->slug]);
        }

        // Se falhar
        return back()->withErrors([
            'email' => 'As credenciais fornecidas estão incorretas.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $slug = $request->route('tenant_slug');
        return redirect()->route('paciente.login', ['tenant_slug' => $slug]);
    }
}