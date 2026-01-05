<?php

namespace App\Filament\App\Widgets;

use App\Models\Appointment;
use App\Models\Exam;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    // Atualiza os números a cada 15 segundos automaticamente
    protected static ?string $pollingInterval = '15s';

    // Define a ordem (opcional, caso tenha outros widgets)
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Pega a clínica atual logada no painel
        $tenant = Filament::getTenant();

        // 1. Busca total de pacientes desta clínica
        $totalPacientes = User::where('tenant_id', $tenant->id)
            ->where('role', 'paciente')
            ->count();

        // 2. Busca agendamentos marcados para HOJE
        $agendamentosHoje = Appointment::where('tenant_id', $tenant->id)
            ->whereDate('data_agendamento', now())
            ->where('status', '!=', 'cancelado') // Ignora cancelados
            ->count();

        // 3. Busca exames que ainda estão 'Em Análise'
        $examesPendentes = Exam::where('tenant_id', $tenant->id)
            ->where('status', 'analise')
            ->count();

        return [
            Stat::make('Pacientes Cadastrados', $totalPacientes)
                ->description('Base total de pacientes')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                // Gráfico decorativo (simulação de crescimento)
                ->chart([1, 5, 10, 8, 15, 12, $totalPacientes]), 

            Stat::make('Agenda Hoje', $agendamentosHoje)
                ->description('Consultas para o dia atual')
                ->descriptionIcon('heroicon-m-calendar-days')
                // Fica verde se tiver gente, cinza se estiver vazio
                ->color($agendamentosHoje > 0 ? 'success' : 'gray'),

            Stat::make('Exames Pendentes', $examesPendentes)
                ->description('Aguardando liberação de laudo')
                ->descriptionIcon('heroicon-m-document-magnifying-glass')
                // Fica amarelo (alerta) se tiver pendência, verde se zerar
                ->color($examesPendentes > 0 ? 'warning' : 'success'),
        ];
    }
}