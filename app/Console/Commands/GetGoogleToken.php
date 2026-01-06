<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Google\Client;
use Google\Service\Drive;

class GetGoogleToken extends Command
{
    protected $signature = 'google:auth {clientId} {clientSecret}';
    protected $description = 'Gera o Refresh Token do Google Drive';

    public function handle()
    {
        $clientId = $this->argument('clientId');
        $clientSecret = $this->argument('clientSecret');

        $client = new Client();
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setAccessType('offline'); // Importante para pegar o Refresh Token
        $client->setPrompt('select_account consent'); // Força perguntar de novo
        $client->addScope(Drive::DRIVE);
        
        // URL especial para copiar o código manualmente
        $client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob'); 

        $authUrl = $client->createAuthUrl();

        $this->info('1. Abra este link no navegador e faça login com a conta que tem espaço:');
        $this->info($authUrl);
        $this->newLine();
        
        $code = $this->ask('2. Cole aqui o código que o Google te deu');

        try {
            $accessToken = $client->fetchAccessTokenWithAuthCode($code);
            
            if (isset($accessToken['error'])) {
                $this->error('Erro: ' . json_encode($accessToken));
                return;
            }

            $this->info('✅ SUCESSO! Copie o Refresh Token abaixo para o seu .env:');
            $this->newLine();
            $this->warn('GOOGLE_DRIVE_REFRESH_TOKEN="' . $accessToken['refresh_token'] . '"');
            $this->newLine();

        } catch (\Exception $e) {
            $this->error('Erro: ' . $e->getMessage());
        }
    }
}