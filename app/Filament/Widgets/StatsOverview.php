<?php

namespace App\Filament\Widgets;

use App\Models\Tenant;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    // Atualiza a cada 30 segundos
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        // DADOS PARA OS GRÁFICOS (Últimos 7 dias)
        $chartPacientes = $this->getChartData(User::class);
        $chartTenants = $this->getChartData(Tenant::class);

        // CRESCIMENTO (Novos este mês)
        $novosPacientesMes = User::where('role', 'paciente')
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();
            
        $novosTenantsMes = Tenant::whereMonth('created_at', Carbon::now()->month)->count();

        return [
            // CARD 1: CLÍNICAS (SEUS CLIENTES)
            Stat::make('Clínicas Ativas', Tenant::where('is_active', true)->count())
                ->description($novosTenantsMes . ' novas este mês')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart($chartTenants), 

            // CARD 2: PACIENTES (UTILIZAÇÃO DO SAAS)
            Stat::make('Pacientes na Base', User::where('role', 'paciente')->count())
                ->description('+' . $novosPacientesMes . ' usuários recentes')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary')
                ->chart($chartPacientes),

            // CARD 3: TECNOLOGIA (STANDALONE vs INTEGRADO)
            Stat::make('Modo de Operação', 
                Tenant::where('mode', 'integrated')->count() . ' Integ. / ' . 
                Tenant::where('mode', 'standalone')->count() . ' Locais'
            )
            // REMOVI A LINHA ->view(...) QUE CAUSAVA O ERRO
            ->description('Hospitais (Tasy/MV) vs Consultórios')
            ->chart([7, 2, 10, 3, 15, 4, 17]) // Gráfico ilustrativo de atividade
            ->color('warning'),
        ];
    }

    // Função auxiliar para gerar dados do gráfico (últimos 7 dias)
    private function getChartData($modelClass)
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            // Verifica se a classe tem soft deletes para ignorar os deletados
            $query = $modelClass::query();
            if (method_exists($modelClass, 'bootSoftDeletes')) {
                $query->whereNull('deleted_at');
            }
            
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $data[] = $query->whereDate('created_at', $date)->count();
        }
        return $data;
    }
}