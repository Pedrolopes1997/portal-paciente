<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule; // Importante

use Spatie\Health\Commands\RunHealthChecksCommand;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('backup:clean')->daily()->at('01:00'); // Limpa backups velhos
Schedule::command('backup:run')->daily()->at('02:00');   // Cria backup novo

// Roda os checks a cada minuto para manter o painel atualizado
Schedule::command(RunHealthChecksCommand::class)->everyMinute();