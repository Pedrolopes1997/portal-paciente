<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tenant;
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

    public function create($tenant_slug)
    {
        $tenant = Tenant::where('slug', $tenant_slug)->firstOrFail();
        return view('auth.primeiro-acesso', compact('tenant'));
    }

    public function store(Request $request, $tenant_slug)
    {
        $tenant = Tenant::where('slug', $tenant_slug)->firstOrFail();

        // 1. Validação dos Campos
        $request->validate([
            'cpf' => 'required|string',
            'nascimento' => 'required|date',
            'email' => 'required|email|unique:users,email', // Email novo não pode existir
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'accepted',
        ]);

        $cpfLimpo = preg_replace('/[^0-9]/', '', $request->cpf);
        
        // --- CENÁRIO A: TASY (INTEGRADO) ---
        if ($tenant->erp_driver === 'tasy') {
            
            // Busca no Tasy
            $pacienteTasy = $this->healthSystem->validarPaciente($cpfLimpo, $request->nascimento);

            if (!$pacienteTasy) {
                return back()->withInput()->withErrors(['main' => 'Dados não conferem com o cadastro hospitalar.']);
            }

            // Verifica se já tem conta vinculada a esse ID do Tasy
            $userExiste = User::where('tenant_id', $tenant->id)
                ->where('tasy_cd_pessoa_fisica', $pacienteTasy->cd_pessoa_fisica)
                ->first();
            
            if ($userExiste) {
                return back()->withErrors(['main' => 'Já existe um usuário para este paciente. Tente "Esqueci a Senha".']);
            }

            // Cria o usuário
            $user = User::create([
                'name' => $pacienteTasy->nm_pessoa_fisica,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'tasy_cd_pessoa_fisica' => $pacienteTasy->cd_pessoa_fisica,
                'tenant_id' => $tenant->id,
                'cpf' => $cpfLimpo,
                'role' => 'paciente'
            ]);

        } 
        // --- CENÁRIO B: LOCAL (PAINEL ADMINISTRATIVO) ---
        else {
            
            // Busca o usuário que a secretária cadastrou no painel (pelo CPF)
            // Mas que AINDA NÃO TEM SENHA (ou é um pré-cadastro)
            $user = User::where('tenant_id', $tenant->id)
                ->where('cpf', $cpfLimpo) // CPF cadastrado no painel
                ->first();

            if (!$user) {
                return back()->withInput()->withErrors(['main' => 'Paciente não encontrado nesta clínica. Entre em contato com a recepção.']);
            }

            // Valida a Data de Nascimento (Segurança Extra)
            if ($user->nascimento && $user->nascimento->format('Y-m-d') !== $request->nascimento) {
                return back()->withInput()->withErrors(['main' => 'Data de nascimento incorreta.']);
            }

            // Se o usuário já tem senha e email, ele não deveria estar aqui
            if (!empty($user->password)) {
                return back()->withErrors(['main' => 'Você já possui cadastro. Faça login ou recupere a senha.']);
            }

            // ATUALIZA o usuário existente com o E-mail e Senha escolhidos
            $user->update([
                'email' => $request->email, // Atualiza o e-mail (pode ser diferente do cadastrado pela secretária)
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);
        }

        // --- PÓS CADASTRO (IGUAL PARA OS DOIS) ---
        if (isset($user)) {
            TermAcceptance::create([
                'user_id' => $user->id,
                'term_version' => 'v1.0',
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);

            Auth::login($user);
            return redirect()->route('paciente.dashboard', ['tenant_slug' => $tenant->slug]);
        }

        return back()->withErrors(['main' => 'Erro desconhecido ao criar usuário.']);
    }
}