<?php

namespace App\Filament\App\Resources\AppointmentResource\Pages;

use App\Filament\App\Resources\AppointmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAppointments extends ListRecords
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Botão para IMPRIMIR
            Actions\Action::make('print')
                ->label('Imprimir do Dia')
                ->icon('heroicon-o-printer')
                ->color('gray') // Cinza visível
                ->url(fn () => route('painel.imprimir.agenda', [
                    'tenant_slug' => \Filament\Facades\Filament::getTenant()->slug, 
                    'tenant' => \Filament\Facades\Filament::getTenant()->slug
                ]), true), // true = Nova Aba

            // Botão para TROCAR DE AGENDA
            Actions\Action::make('change_agenda')
                ->label('Trocar Agenda')
                ->icon('heroicon-m-arrow-left')
                ->color('gray') // Mudei de 'secondary' para 'gray' para garantir visibilidade
                ->url(AppointmentResource::getUrl('index')), // Sem 'true', abre na mesma aba

            Actions\CreateAction::make()->label('Novo Agendamento'),
        ];
    }
}