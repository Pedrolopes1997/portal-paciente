<?php

namespace App\Filament\App\Resources\AppointmentResource\Pages;

use App\Filament\App\Resources\AppointmentResource;
use Filament\Resources\Pages\Page;
use App\Models\User;
use App\Models\Specialty;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;

class AgendaSelection extends Page
{
    protected static string $resource = AppointmentResource::class;

    protected static string $view = 'filament.app.resources.appointment-resource.pages.agenda-selection';

    protected static ?string $title = 'Agenda';

    // Variáveis independentes para cada lado
    public $doctor_id = null;
    public $specialty_id = null;

    // Listas
    public $doctors_list = [];
    public $specialties_list = [];

    public function mount()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()->id;

        // Médicos da clínica
        $this->doctors_list = \App\Models\User::where('tenant_id', $tenantId)
            ->where('role', 'doctor')
            ->pluck('name', 'id');

        // Especialidades do tipo 'medica' (para o lado de consultas)
        // Nota: Se você quiser filtrar especialidades por clínica no futuro, adicione o where('tenant_id')
        $this->specialties_list = \App\Models\Specialty::where('type', 'exame')
            ->pluck('name', 'id');
            
        // Caso queira que a lista de médicos só apareça se houver especialidades médicas:
        // $this->medical_specialties = \App\Models\Specialty::where('type', 'medica')->get();
    }

    // Ação para Consultas
    public function irParaConsultas()
    {
        if (!$this->doctor_id) {
            Notification::make()->title('Selecione um médico primeiro.')->warning()->send();
            return;
        }

        // Removido 'activeTab' => 'consultas'
        return redirect()->to(AppointmentResource::getUrl('list', [
            'tableFilters' => ['doctor_id' => ['value' => $this->doctor_id]]
        ]));
    }

    // Ação para Exames
    public function irParaExames()
    {
        if (!$this->specialty_id) {
            Notification::make()->title('Selecione o procedimento primeiro.')->warning()->send();
            return;
        }

        // Removido 'activeTab' => 'exames'
        return redirect()->to(AppointmentResource::getUrl('list', [
            'tableFilters' => ['specialty_id' => ['value' => $this->specialty_id]]
        ]));
    }
}