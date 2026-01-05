<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Tasy\TasyPacienteService;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    protected $tasyService;

    public function __construct(TasyPacienteService $tasyService)
    {
        $this->tasyService = $tasyService;
    }

    public function dashboard(Request $request)
    {
        // O usuário já estará autenticado pelo Laravel Sanctum
        $user = $request->user();
        
        // O 'tasy_id' é um campo que criaremos na tabela users do MySQL
        // para vincular o login do portal ao ID real do Tasy
        $exames = $this->tasyService->buscarExamesRecentes($user->tasy_id);

        return response()->json([
            'paciente' => $user->name,
            'exames_recentes' => $exames,
            'notificacoes' => 0
        ]);
    }
}
