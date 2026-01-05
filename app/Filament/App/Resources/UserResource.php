<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $modelLabel = 'Paciente';
    protected static ?string $pluralModelLabel = 'Pacientes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Cadastro do Paciente')
                    ->tabs([
                        // ABA 1: DADOS BÁSICOS
                        Forms\Components\Tabs\Tab::make('Dados Pessoais')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->label('Nome Completo'),
                                
                                Forms\Components\TextInput::make('cpf')
                                    ->label('CPF')
                                    ->mask('999.999.999-99'),

                                Forms\Components\TextInput::make('cns')
                                    ->label('Cartão SUS (CNS)')
                                    ->mask('999999999999999'),

                                Forms\Components\DatePicker::make('nascimento')
                                    ->label('Data de Nascimento')
                                    ->displayFormat('d/m/Y') // <--- COMO O USUÁRIO VÊ
                                    ->format('Y-m-d')        // <--- COMO O BANCO RECEBE (MySQL)
                                    ->native(false)          // <--- Usa o calendário bonito do Filament (JS) em vez do nativo do browser
                                    ->maxDate(now()),        // (Opcional) Impede datas futuras
                                
                                Forms\Components\TextInput::make('nome_mae')
                                    ->label('Nome da Mãe'),
                                
                                Forms\Components\TextInput::make('celular')
                                    ->label('Celular / WhatsApp')
                                    ->mask('(99) 99999-9999'),
                            ])->columns(2),

                        // ABA 2: ENDEREÇO
                        Forms\Components\Tabs\Tab::make('Endereço')
                            ->schema([
                                Forms\Components\TextInput::make('cep')
                                    ->label('CEP')
                                    ->mask('99999-999')
                                    ->columnSpan(1),
                                
                                Forms\Components\TextInput::make('endereco')
                                    ->label('Rua / Avenida')
                                    ->columnSpan(2), // Ocupa 2 espaços

                                Forms\Components\TextInput::make('numero')
                                    ->label('Número')
                                    ->columnSpan(1),

                                // --- NOVO CAMPO ---
                                Forms\Components\TextInput::make('complemento')
                                    ->label('Complemento')
                                    ->placeholder('Ex: Ap 102, Bloco C')
                                    ->columnSpan(2), // Ocupa 2 espaços para ficar largo

                                Forms\Components\TextInput::make('bairro')
                                    ->label('Bairro')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('cidade')
                                    ->label('Cidade')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('uf')
                                    ->label('UF')
                                    ->maxLength(2)
                                    ->columnSpan(1),
                            ])->columns(3), // Grid de 3 colunas
                            
                        // ABA 3: ACESSO (LOGIN)
                        Forms\Components\Tabs\Tab::make('Acesso ao Portal')
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->label('E-mail de Login'),

                                Forms\Components\TextInput::make('password')
                                    ->password()
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->label('Senha'),
                                
                                // Campo Oculto para forçar role 'paciente' neste painel
                                Forms\Components\Hidden::make('role')
                                    ->default('paciente'),
                                
                                // O tenant_id é injetado automaticamente pelo Scopo do Filament App,
                                // mas se precisar forçar:
                                // Forms\Components\Hidden::make('tenant_id')
                                //    ->default(auth()->user()->tenant_id),
                            ]),
                    ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('cpf')
                    ->label('CPF')
                    ->searchable(),

                Tables\Columns\TextColumn::make('celular')
                    ->label('Celular'),

                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}