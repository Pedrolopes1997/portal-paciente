<?php

namespace App\Filament\Widgets;

use App\Models\Tenant;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Clínicas Ativas', Tenant::where('is_active', true)->count())
                ->description('Total de clientes operando')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('success'),

            Stat::make('Pacientes Cadastrados', User::where('role', 'paciente')->count())
                ->description('Em todas as bases')
                ->color('primary'),

            Stat::make('Integrações Tasy', Tenant::where('erp_driver', 'tasy')->count())
                ->description('Hospitais usando Oracle')
                ->color('warning'),
        ];
    }
}