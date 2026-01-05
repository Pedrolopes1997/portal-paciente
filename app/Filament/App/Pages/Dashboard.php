<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    // --- COMENTE OU REMOVA ESTA LINHA ---
    // Ao remover a view personalizada, o Filament usa o layout padrão 
    // que já sabe organizar os Widgets automaticamente.
    // protected static string $view = 'filament.app.pages.dashboard';

    protected static ?string $slug = '/';
    protected static ?string $title = 'Início';
}