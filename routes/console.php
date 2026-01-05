<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule; // Importante

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ADICIONE ESTA LINHA:
Schedule::command('backup:clean')->daily()->at('01:00'); // Limpa backups velhos
Schedule::command('backup:run')->daily()->at('02:00');   // Cria backup novo