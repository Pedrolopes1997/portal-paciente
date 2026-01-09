<?php

namespace App\Livewire\Patient;

use Livewire\Component;
use App\Services\Drivers\HealthSystemInterface;
use App\Models\Tenant;
use App\Models\Specialty; // Importante para buscar o nome do exame
use App\Models\User;      // Importante para buscar o nome do médico
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NewAppointment extends Component
{
    public Tenant $tenant;
    public $appointmentType = null; 
    public $step = 0; 
    
    public $specialties = [];
    public $doctors = [];
    public $slots = [];
    public $selectedSpecialtyId = null;
    public $selectedDoctorId = null;
    public $selectedDate = null;
    public $selectedSlot = null; 
    public $notes = '';
    public $successMessage = false;

    public function mount(HealthSystemInterface $service)
    {
        $slug = request()->route('tenant_slug');
        $this->tenant = Tenant::where('slug', $slug)->firstOrFail();
        $this->selectedDate = Carbon::today()->format('Y-m-d');
    }

    public function selectType($type, HealthSystemInterface $service)
    {
        $this->appointmentType = $type;
        
        // Mapeia o tipo da URL para o tipo salvo no banco de dados
        $tipoNoBanco = ($type === 'consulta') ? 'medica' : 'exame';

        // Buscamos como Objetos para não quebrar o HTML (Blade)
        $this->specialties = \App\Models\Specialty::where('type', $tipoNoBanco)
            ->orderBy('name', 'asc')
            ->get();
        
        // Se a lista estiver vazia (para evitar que a tela fique branca se você ainda não categorizou no banco)
        if ($this->specialties->isEmpty()) {
            $this->specialties = \App\Models\Specialty::all();
        }
        
        $this->step = 1; 
    }

    public function selectSpecialty($id, HealthSystemInterface $service)
    {
        $this->selectedSpecialtyId = $id;
        
        // Se for EXAME, pulamos a escolha de médico e vamos direto para data/hora
        if ($this->appointmentType === 'exame') {
            $this->selectedDoctorId = null;
            // Para exames, usamos um "médico genérico" ou o próprio ID da especialidade no driver se necessário
            // Aqui vamos carregar os slots direto para o procedimento
            $this->loadSlots($service);
            $this->step = 3; 
        } else {
            $this->doctors = $service->buscarMedicos($id);
            $this->step = 2; 
        }
    }

    public function selectDoctor($id, HealthSystemInterface $service)
    {
        $this->selectedDoctorId = $id;
        $this->loadSlots($service); 
        $this->step = 3; 
    }

    public function updatedSelectedDate()
    {
        $this->loadSlots(app(HealthSystemInterface::class));
        $this->selectedSlot = null; 
    }

    public function loadSlots(HealthSystemInterface $service)
    {
        // Se for exame, passamos o ID da especialidade como referência de agenda se o driver suportar
        // Ou passamos null no doctor_id
        $idReferencia = ($this->appointmentType === 'exame') ? null : $this->selectedDoctorId;
        
        if ($this->selectedDate) {
            $this->slots = $service->buscarHorariosDisponiveis($idReferencia, $this->selectedDate);
        }
    }

    public function confirmAppointment(HealthSystemInterface $service)
    {
        // VALIDAÇÃO AJUSTADA
        $this->validate([
            'appointmentType' => 'required',
            'selectedSpecialtyId' => 'required',
            'selectedDoctorId' => $this->appointmentType === 'consulta' ? 'required' : 'nullable', // Médico opcional para exames
            'selectedDate' => 'required',
            'selectedSlot' => 'required',
        ]);

        $fullDate = Carbon::parse($this->selectedDate . ' ' . $this->selectedSlot);

        // BUSCA O NOME PARA O CAMPO 'MEDICO' DO BANCO
        if ($this->appointmentType === 'exame') {
            $especialidade = Specialty::find($this->selectedSpecialtyId);
            $nomeExibicao = $especialidade ? $especialidade->name : 'Exame';
        } else {
            $medico = User::find($this->selectedDoctorId);
            $nomeExibicao = $medico ? $medico->name : 'Médico';
        }

        $service->criarAgendamento([
            'tenant_id' => $this->tenant->id,
            'type' => $this->appointmentType,
            'patient_id' => Auth::id(),
            'doctor_id' => $this->selectedDoctorId,
            'specialty_id' => $this->selectedSpecialtyId,
            'scheduled_at' => $fullDate,
            'medico' => $nomeExibicao, // <--- AQUI SALVA O NOME CORRETO
            'notes' => $this->notes,
            'status' => 'pending' // Garante que caia como pendente para a recepcionista confirmar
        ]);

        $this->successMessage = true;
        $this->step = 4; 
    }

    public function back()
    {
        if ($this->step == 3 && $this->appointmentType === 'exame') {
            $this->step = 1; // Pula a volta do médico se for exame
        } elseif ($this->step == 1) {
            $this->appointmentType = null;
            $this->step = 0;
        } elseif ($this->step > 0) {
            $this->step--;
        }
    }

    public function render()
    {
        return view('livewire.patient.new-appointment')
            ->layoutData(['tenant' => $this->tenant]);
    }
}