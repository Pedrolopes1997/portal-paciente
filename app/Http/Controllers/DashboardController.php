<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Drivers\HealthSystemInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected $healthSystem;

    public function __construct(HealthSystemInterface $healthSystem)
    {
        $this->healthSystem = $healthSystem;
    }

    // AJUDANTE: Pega o ID correto (Tasy ou Local)
    private function getPacienteId($user)
    {
        if ($user->tenant && $user->tenant->erp_driver === 'tasy') {
             return $user->tasy_cd_pessoa_fisica;
        }
        return $user->id;
    }

    // AJUDANTE: Formata exames para Array e gera Links (Resolve o erro da View)
    private function formatarExames($examesRaw, $tenant)
    {
        return $examesRaw->map(function($exame) use ($tenant) {
            $status = strtoupper($exame->status ?? '');
            $isLiberado = in_array($status, ['L', 'LIB', 'LIBERADO', 'PUBLICADO']);

            $link = '#';
            if ($isLiberado) {
                // Gera a rota para visualizar o PDF
                $link = route('paciente.exames.visualizar', [
                    'tenant_slug' => $tenant->slug ?? 'default', 
                    'id' => $exame->id_exame ?? 0
                ]);
            }

            // Retorna como ARRAY para a View funcionar
            return [
                'id_pdf'      => $exame->id_exame ?? 0, 
                'descricao'   => $exame->descricao ?? 'Exame',
                'data'        => isset($exame->data) ? Carbon::parse($exame->data)->format('d/m/Y') : '--',
                'status'      => $isLiberado ? 'Liberado' : 'Em Análise',
                'link_laudo'  => $link,
            ];
        });
    }

    // PAGINA 1: DASHBOARD (Resumo)
    public function index()
    {
        $user = Auth::user();
        $tenant = $user->tenant;

        // Limpeza total: O Livewire faz o trabalho pesado agora
        return view('dashboard', compact('tenant'));
    }
    
    // PAGINA 2: AGENDA COMPLETA
    public function agendamentos()
    {
        $user = Auth::user();
        $tenant = $user->tenant;
        
        // Removemos a busca $this->healthSystem->buscarAgendamentos...
        // Passamos vazios, pois a view não usa mais eles (usa Livewire)
        $agendamentos = collect([]);

        return view('paciente.agenda', compact('agendamentos', 'tenant', 'user'));
    }

    /// PAGINA 3: EXAMES COMPLETOS
    public function exames()
    {
        $user = Auth::user();
        $tenant = $user->tenant;
        
        // REMOVEMOS toda a lógica de busca lenta daqui.
        // Passamos apenas o $tenant e $user para a view.
        // O Livewire fará a busca assíncrona.

        // Passamos uma coleção vazia de $exames só para não quebrar 
        // caso alguma lógica antiga ainda dependa, mas a view nova nem usa isso.
        $exames = collect([]); 

        return view('paciente.exames', compact('exames', 'tenant', 'user'));
    }

    public function cancelarAgendamento($tenant_slug, $id) 
    {
        $user = Auth::user();
        // Lógica local de cancelamento...
        try {
            $agendamento = \App\Models\Appointment::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if ($agendamento) {
                $agendamento->status = 'cancelado';
                $agendamento->save();
            }
            return back()->with('success', 'Solicitação processada.');
        } catch (\Exception $e) {
             return back()->with('error', 'Erro ao cancelar.');
        }
    }
}