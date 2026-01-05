<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tenant; // Importante
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\Drivers\HealthSystemInterface;
use App\Models\TermAcceptance;

class FirstAccessController extends Controller
{
    protected $healthSystem;

    public function __construct(HealthSystemInterface $healthSystem)
    {
        $this->healthSystem = $healthSystem;
    }

    // 1. Exibir o Formulário
    // CORREÇÃO: Adicionamos $tenant_slug como argumento, vindo da Rota
    public function create($tenant_slug)
    {
        // Buscamos o Tenant pelo Slug da URL
        $tenant = Tenant::where('slug', $tenant_slug)->firstOrFail();

        return view('auth.primeiro-acesso', compact('tenant'));
    }

    // 2. Processar o Cadastro
    // CORREÇÃO: Adicionamos $tenant_slug aqui também
    public function store(Request $request, $tenant_slug)
    {
        $tenant = Tenant::where('slug', $tenant_slug)->firstOrFail();

        // Validação Básica
        $request->validate([
            'cpf' => 'required|string',
            'nascimento' => 'required|date',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'accepted', // <--- OBRIGATÓRIO: Tem que ser true/on
        ], [
            'terms.accepted' => 'Você precisa ler e aceitar os Termos de Uso para continuar.' // Mensagem personalizada
        ]);

        $cpfLimpo = preg_replace('/[^0-9]/', '', $request->cpf);
        
        // --- CENÁRIO A: TASY (Validação Rígida) ---
        if ($tenant->erp_driver === 'tasy') {
            
            // Pergunta ao Oracle se o paciente existe
            $pacienteTasy = $this->healthSystem->validarPaciente($cpfLimpo, $request->nascimento);

            if (!$pacienteTasy) {
                return back()
                    ->withInput()
                    ->withErrors(['main' => 'Dados não conferem com nosso cadastro hospitalar. Verifique o CPF e Data de Nascimento.']);
            }

            // Se achou, verifica se já tem usuário criado para este ID do Tasy
            // Nota: Ajuste 'cd_pessoa_fisica' conforme o retorno exato do seu Driver (maiúscula ou minúscula)
            $userExiste = User::where('tasy_cd_pessoa_fisica', $pacienteTasy->cd_pessoa_fisica ?? $pacienteTasy->CD_PESSOA_FISICA)->first();
            
            if ($userExiste) {
                return back()->withErrors(['main' => 'Já existe um usuário cadastrado para este paciente. Tente recuperar a senha.']);
            }

            // CRIA O USUÁRIO VINCULADO
            $user = User::create([
                'name' => $pacienteTasy->nm_pessoa_fisica ?? $pacienteTasy->NM_PESSOA_FISICA,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'tasy_cd_pessoa_fisica' => $pacienteTasy->cd_pessoa_fisica ?? $pacienteTasy->CD_PESSOA_FISICA,
                'tenant_id' => $tenant->id,
            ]);

        } 
        // --- CENÁRIO B: LOCAL (Auto-cadastro Simples) ---
        else {
            $user = User::create([
                'name' => 'Paciente (Auto-cadastro)',
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'tenant_id' => $tenant->id,
            ]);
        }

        if (isset($user)) {
            TermAcceptance::create([
                'user_id' => $user->id,
                'term_version' => 'v1.0', // Se mudar os termos no futuro, mude para v1.1
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);
        }

        // Loga o usuário e manda pro Dashboard
        Auth::login($user);

        return redirect()->route('paciente.dashboard', ['tenant_slug' => $tenant->slug]);
    }
}