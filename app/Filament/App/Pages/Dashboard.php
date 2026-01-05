<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.app.pages.dashboard';

    // ADICIONE ESTAS DUAS LINHAS PARA GARANTIR QUE ELE SEJA A HOME
    protected static ?string $slug = '/';
    protected static ?string $title = 'Início';
}