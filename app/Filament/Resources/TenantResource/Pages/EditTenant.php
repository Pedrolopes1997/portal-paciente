<?php

namespace App\Filament\Resources\TenantResource\Pages;

use App\Filament\Resources\TenantResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;

class EditTenant extends EditRecord
{
    protected static string $resource = TenantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Botão de Teste de Conexão
            Actions\Action::make('test_connection')
                ->label('Testar Conexão ERP')
                ->color('warning')
                ->icon('heroicon-o-signal')
                ->visible(fn ($record) => $record->mode === 'integrated')
                ->action(function ($record) {
                    try {
                        // 1. Pega os dados
                        $dados = $record->db_connection_data;
                        
                        if (empty($dados)) {
                            throw new \Exception('Dados de conexão não preenchidos.');
                        }

                        // 2. Configura uma conexão temporária de teste
                        Config::set('database.connections.test_temp', [
                            'driver'   => 'oracle',
                            'host'     => $dados['db_host'],
                            'port'     => $dados['db_port'],
                            'database' => $dados['db_database'],
                            'username' => $dados['db_username'],
                            'password' => $dados['db_password'],
                            'charset'  => 'WE8MSWIN1252',
                        ]);
                        
                        DB::purge('test_temp');
                        
                        // 3. Tenta fazer um SELECT simples (dual é tabela padrão Oracle)
                        DB::connection('test_temp')->getPdo();
                        
                        // Se não deu erro no getPdo(), sucesso!
                        Notification::make()
                            ->title('Conexão Estabelecida com Sucesso!')
                            ->body('O sistema conseguiu conversar com o servidor Oracle.')
                            ->success()
                            ->send();

                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Falha na Conexão')
                            ->body('Erro: ' . $e->getMessage())
                            ->danger()
                            ->persistent() // O erro fica na tela até fechar
                            ->send();
                    }
                }),

            // NOVA AÇÃO: Criar Usuário Admin
                    Actions\Action::make('create_admin')
                        ->label('Novo Admin')
                        ->icon('heroicon-o-user-plus')
                        ->color('success')
                        ->form([
                            TextInput::make('name')->label('Nome')->required(),
                            TextInput::make('email')->label('E-mail')->email()->required(),
                            TextInput::make('password')->label('Senha')->password()->required(),
                        ])
                        ->action(function (array $data, $record) {
                            // Cria o usuário vinculado a ESTE tenant ($record)
                            User::create([
                                'name' => $data['name'],
                                'email' => $data['email'],
                                'password' => Hash::make($data['password']),
                                'tenant_id' => $record->id, // Vínculo automático
                                'role' => 'admin_clinica', // Papel automático
                            ]);

                            Notification::make()->title('Admin criado com sucesso!')->success()->send();
                        }),

                    Actions\DeleteAction::make(),
                ];
            }
}