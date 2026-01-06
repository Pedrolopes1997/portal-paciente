<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Storage;

class UploadBackupToDrive extends Command
{
    // O nome do comando que vamos rodar no terminal
    protected $signature = 'backup:drive-force';
    protected $description = 'Envia o último backup local para o Google Drive na força bruta';

    public function handle()
    {
        $this->info('🚀 Iniciando processo de Backup + Upload Forçado...');

        // 1. Gera o backup APENAS localmente (para não dar erro no Google)
        $this->info('📦 Gerando arquivo .zip local...');
        $exitCode = $this->call('backup:run', ['--only-to-disk' => 'local']);

        if ($exitCode !== 0) {
            $this->error('❌ Falha ao criar o backup local.');
            return;
        }

        // 2. Encontra o arquivo .zip mais recente na pasta local
        $this->info('🔍 Procurando o arquivo mais recente...');
        
        // Ajuste aqui se sua pasta de backup for diferente (ex: 'WeCare')
        // Se você deixou 'name' => '' no config, deve estar na raiz.
        // Se deixou 'name' => 'WeCare', mude para: $files = Storage::disk('local')->files('WeCare');
        $files = Storage::disk('local')->files('WeCare'); 
        
        // Se não achou na pasta WeCare, tenta na raiz
        if (empty($files)) {
             $files = Storage::disk('local')->files();
        }

        $zips = array_filter($files, fn($f) => str_ends_with($f, '.zip'));
        
        if (empty($zips)) {
            $this->error('❌ Nenhum arquivo .zip encontrado para upload.');
            return;
        }

        // Pega o último modificado
        $latestZip = null;
        $latestTime = 0;
        foreach ($zips as $zip) {
            $time = Storage::disk('local')->lastModified($zip);
            if ($time > $latestTime) {
                $latestTime = $time;
                $latestZip = $zip;
            }
        }

        $fullPath = Storage::disk('local')->path($latestZip);
        $fileName = basename($latestZip);
        $this->info("📄 Arquivo encontrado: $fileName");

        // 3. Prepara o Google Drive (Código do Detector de Mentiras)
        $folderId = config('filesystems.disks.google.folder');
        $jsonPath = config('filesystems.disks.google.serviceAccount');

        $client = new Client();
        $client->setAuthConfig($jsonPath);
        $client->addScope(Drive::DRIVE);
        $service = new Drive($client);

        // 4. Faz o Upload
        $this->info('☁️ Enviando para o Google Drive...');
        
        $fileMetadata = new DriveFile([
            'name' => $fileName,
            'parents' => [$folderId]
        ]);

        try {
            $content = file_get_contents($fullPath);
            $file = $service->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => 'application/zip',
                'uploadType' => 'multipart',
                'fields' => 'id'
            ]);

            $this->info("✅ SUCESSO! Upload concluído. ID: " . $file->id);
            
            // Opcional: Apagar o local depois de subir?
            // Storage::disk('local')->delete($latestZip); 

        } catch (\Exception $e) {
            $this->error("❌ Erro no Upload: " . $e->getMessage());
        }
    }
}