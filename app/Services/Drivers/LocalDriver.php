<?php

namespace App\Services\Drivers;

use App\Models\Exam;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Specialty;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class LocalDriver implements HealthSystemInterface
{
    protected Tenant $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    public function buscarExames($idPaciente)
    {
        // Verifica qual coluna usar (compatibilidade)
        $colunaPaciente = \Schema::hasColumn('exams', 'patient_id') ? 'patient_id' : 'user_id';

        return Exam::where('tenant_id', $this->tenant->id)
            ->where($colunaPaciente, $idPaciente)
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($exame) {
                
                $dataObj = $exame->date;
                if (!($dataObj instanceof \Carbon\Carbon)) {
                    $dataObj = \Carbon\Carbon::parse($exame->date);
                }

                $statusLimpo = strtolower($exame->status);
                $isLiberado = ($statusLimpo === 'liberado' || $statusLimpo === 'concluido');

                return (object) [
                    'id_exame'  => $exame->id,
                    'descricao' => $exame->title ?? 'Exame sem título',
                    'data'      => $dataObj->format('Y-m-d'),
                    'status'    => $isLiberado ? 'LIB' : 'PEN',
                    'status_laudo' => $isLiberado ? 'LL' : 'EA', 
                    'url_pdf'   => $exame->file_path, 
                ];
            });
    }

    public function buscarUltimoConvenio($idPaciente)
    {
        $user = User::find($idPaciente);

        if (!$user) {
            return (object) ['convenio' => 'Particular', 'carteirinha' => '---'];
        }

        // --- Prioridade Absoluta para o Cadastro no Painel ---
        if (!empty($user->convenio)) {
            return (object) [
                'convenio' => $user->convenio,
                'carteirinha' => $user->carteirinha ?? '---'
            ];
        }

        // Fallback
        $nomeConvenio = $user->plano_saude 
                      ?? $user->insurance_name 
                      ?? 'Particular';

        $numCarteirinha = $user->numero_carteirinha 
                        ?? $user->insurance_number 
                        ?? '---';

        // Histórico de agendamentos
        if ($nomeConvenio === 'Particular') {
            $lastAppointment = \App\Models\Appointment::where('patient_id', $idPaciente)
                ->orderBy('scheduled_at', 'desc')
                ->first();
            
            if ($lastAppointment && !empty($lastAppointment->integration_payload['convenio'])) {
                 $nomeConvenio = $lastAppointment->integration_payload['convenio'];
            }
        }

        return (object) [
            'convenio' => $nomeConvenio,
            'carteirinha' => $numCarteirinha
        ];
    }

    public function buscarEspecialidades(string $tipo = 'consulta')
    {
        return \App\Models\Specialty::whereHas('doctors', function($q) {
             $q->where('tenant_id', $this->tenant->id);
        })
        ->where('type', $tipo)
        ->get();
    }

    public function buscarMedicos($idEspecialidade = null)
    {
        $query = User::where('tenant_id', $this->tenant->id)
                     ->where('role', 'doctor');

        if ($idEspecialidade) {
            $query->whereHas('specialties', function($q) use ($idEspecialidade) {
                $q->where('specialties.id', $idEspecialidade);
            });
        }

        return $query->get();
    }

    public function buscarHorariosDisponiveis($idMedico, string $data)
    {
        $horaInicio = '08:00:00';
        $horaFim    = '18:00:00';
        $duracao    = 30;

        if ($idMedico) {
            $dataCarbon = Carbon::parse($data);
            $diaSemana  = $dataCarbon->dayOfWeek; 

            $grade = \App\Models\Schedule::where('tenant_id', $this->tenant->id)
                ->where('user_id', $idMedico)
                ->where('day_of_week', $diaSemana)
                ->first();

            if (!$grade) {
                return [];
            }

            $horaInicio = $grade->start_time->format('H:i:s');
            $horaFim    = $grade->end_time->format('H:i:s');
            $duracao    = $grade->duration_minutes;
        }

        $inicioExpediente = Carbon::parse("$data $horaInicio");
        $fimExpediente    = Carbon::parse("$data $horaFim");
        
        $periodo = CarbonPeriod::create($inicioExpediente, "$duracao minutes", $fimExpediente);
        $slots = [];

        $queryOcupados = Appointment::where('tenant_id', $this->tenant->id)
            ->whereDate('scheduled_at', $data)
            ->whereIn('status', ['pending', 'confirmed', 'agendado']);

        if ($idMedico) {
            $queryOcupados->where('doctor_id', $idMedico);
        }
        
        $ocupados = $queryOcupados->pluck('scheduled_at')
            ->map(function ($date) {
                return Carbon::parse($date)->format('H:i');
            })
            ->toArray();

        foreach ($periodo as $dt) {
            if ($dt->copy()->addMinutes($duracao)->gt($fimExpediente)) {
                continue;
            }

            $horaFormatada = $dt->format('H:i');

            if (!in_array($horaFormatada, $ocupados)) {
                $slots[] = [
                    'time' => $horaFormatada,
                    'datetime' => $dt->format('Y-m-d H:i:s'),
                    'available' => true,
                    'doctor_id' => $idMedico
                ];
            }
        }

        return $slots;
    }

    public function criarAgendamento(array $dados)
    {
        return Appointment::create([
            'tenant_id' => $this->tenant->id,
            'patient_id' => $dados['patient_id'], 
            'doctor_id' => $dados['doctor_id'] ?? null,
            'specialty_id' => $dados['specialty_id'] ?? null,
            'scheduled_at' => $dados['scheduled_at'],
            'status' => 'pending', 
            'integration_source' => 'local', 
            'patient_notes' => $dados['notes'] ?? null,
        ]);
    }

    public function buscarPaciente(string $cpf, string $nascimento)
    {
        return null;
    }

    // --- MUDANÇA AQUI: Filtro para mostrar apenas Futuros por padrão ---
    // Adicionei o parametro $incluirPassados = false
    public function buscarAgendamentos($idPaciente, $incluirPassados = false)
    {
        $query = \App\Models\Appointment::query()
            ->with(['specialty', 'doctor']) 
            ->where('patient_id', $idPaciente) 
            ->where('tenant_id', $this->tenant->id)
            ->where('status', '!=', 'cancelado');

        // Se NÃO pedimos os passados (padrão do dashboard), filtra data >= hoje (00:00)
        if (!$incluirPassados) {
            $query->where('scheduled_at', '>=', Carbon::now()->startOfDay());
        }

        // Ordena para o mais próximo aparecer primeiro
        $query->orderBy('scheduled_at', 'asc');

        $agendamentos = $query->get();

        return $agendamentos->map(function ($agenda) {
            if ($agenda->specialty) {
                $agenda->type = $agenda->specialty->type ?? 'consulta';
            } else {
                $agenda->type = 'consulta';
            }

            if (empty($agenda->medico) && $agenda->doctor) {
                $agenda->medico = $agenda->doctor->name;
            }

            return $agenda;
        });
    }

    public function obterPdfLaudo($idExame)
    {
        $exame = Exam::find($idExame);

        if (!$exame || !$exame->file_path) {
            return null;
        }

        $caminhoCompleto = storage_path('app/public/' . $exame->file_path);

        if (file_exists($caminhoCompleto)) {
            return $caminhoCompleto;
        }

        return null;
    }

    public function validarPaciente($cpf, $dataNascimento)
    {
        return null;
    }

    public function buscarDetalhesPaciente($idPaciente)
    {
        $user = User::find($idPaciente);

        if (!$user) return null;

        return (object) [
            'nome' => $user->name,
            'cpf' => $user->cpf ?? '---',
            'cns' => $user->cns ?? '---',
            'nome_mae' => $user->nome_mae ?? '---',
            'nascimento' => $user->nascimento ? \Carbon\Carbon::parse($user->nascimento)->format('d/m/Y') : '--/--/--',
            'endereco' => $user->endereco,
            'numero' => $user->numero,
            'complemento' => $user->complemento,
            'bairro' => $user->bairro,
            'cidade' => $user->cidade,
            'uf' => $user->uf,
            'cep' => $user->cep,
            'celular' => $user->celular,
            'email_tasy' => $user->email 
        ];
    }
    
    public function checkPassword(string $cpf, string $password): bool
    {
        return true; 
    }
    
    public function buscarPacientePorCpf(string $cpf)
    {
        return null;
    }
}