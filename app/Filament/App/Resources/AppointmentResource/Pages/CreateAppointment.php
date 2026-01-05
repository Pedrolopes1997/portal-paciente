<?php

namespace App\Filament\App\Resources\AppointmentResource\Pages;

use App\Filament\App\Resources\AppointmentResource;
use App\Mail\AppointmentCreatedMail; // <--- Importe
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail; // <--- Importe

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    // Este método roda automaticamente APÓS salvar no banco
    protected function afterCreate(): void
    {
        // 1. Pega o registro que acabou de ser criado ($this->record)
        $agendamento = $this->record;

        // 2. Envia o e-mail para o paciente
        if ($agendamento->user && $agendamento->user->email) {
            Mail::to($agendamento->user->email)
                ->send(new AppointmentCreatedMail($agendamento));
        }
    }
}