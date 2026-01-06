<?php

namespace App\Checks;

use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupCheck extends Check
{
    public function run(): Result
    {
        $files = Storage::disk('local')->files(); 
        
        $newestBackup = null;
        $lastModified = 0;

        foreach ($files as $file) {
            if (str_ends_with($file, '.zip')) {
                $time = Storage::disk('local')->lastModified($file);
                if ($time > $lastModified) {
                    $lastModified = $time;
                    $newestBackup = $file;
                }
            }
        }

        if (! $newestBackup) {
            // CORREÇÃO: Usamos Result::make() antes de chamar failed()
            return Result::make()->failed('Nenhum backup encontrado na pasta local!');
        }

        $backupDate = Carbon::createFromTimestamp($lastModified);
        
        if ($backupDate->diffInHours() > 26) {
            return Result::make()->failed("Último backup é muito antigo: " . $backupDate->diffForHumans());
        }

        // CORREÇÃO: Result::make() cria um status de "Sucesso/Ok" por padrão com a mensagem
        return Result::make()
            ->shortSummary("Backup recente encontrado")
            ->notificationMessage("Backup OK: " . basename($newestBackup));
    }
}