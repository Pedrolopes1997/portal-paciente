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
use App\Filament\App\Resources\UserResource\RelationManagers;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Get;

class UserResource extends Resource
{
    protected static ?int $navigationSort = 2;

    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $modelLabel = 'Usuário';
    protected static ?string $pluralModelLabel = 'Usuários';
    
    protected static ?string $navigationGroup = 'Cadastros';
    protected static ?string $navigationLabel = 'Usuários e Médicos';

    public static function getEloquentQuery(): Builder
    {
        // TRUQUE: Começamos uma query nova direto do Model User
        // Isso ignora os escopos automáticos que o Filament tenta injetar
        return User::query()
            ->where(function (Builder $query) {
                // LÓGICA:
                // 1. Mostra usuários desta clínica específica
                $query->where('tenant_id', filament()->getTenant()->id)
                
                // 2. OU mostra Super Admins (mesmo que tenant_id seja nulo)
                      ->orWhere('role', 'super_admin');
            })
            // 3. Garante que pacientes nunca apareçam nessa lista administrativa
            ->where('role', '!=', 'patient');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Cadastro de Usuário')
                    ->tabs([
                        // ABA 1: DADOS PESSOAIS
                        Forms\Components\Tabs\Tab::make('Dados Pessoais')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->label('Nome Completo'),
                                
                                Forms\Components\TextInput::make('cpf')
                                    ->label('CPF')
                                    ->mask('999.999.999-99'),

                                Forms\Components\Toggle::make('is_patient')
                                    ->label('Este usuário também é Paciente?')
                                    ->helperText('Se marcado, ele aparecerá na lista de Pacientes e poderá ter prontuário, mesmo sendo Médico ou Admin.')
                                    ->default(false),

                                Forms\Components\TextInput::make('celular')
                                    ->label('Celular / WhatsApp')
                                    ->mask('(99) 99999-9999'),

                                Forms\Components\Select::make('role')
                                    ->label('Perfil de Acesso')
                                    ->options([
                                        'admin' => 'Admin', // Nome do role local pode variar, verifique se usa 'admin' ou 'admin_clinica'
                                        'doctor' => 'Médico',
                                        'receptionist' => 'Recepção',
                                        'super_admin' => 'Super Admin'
                                    ])
                                    ->required()
                                    ->live(),

                                Forms\Components\TextInput::make('crm')
                                    ->label('CRM / Registro')
                                    ->visible(fn (Get $get) => $get('role') === 'doctor'),
                            ])->columns(2),

                        // ABA 2: ENDEREÇO
                        Forms\Components\Tabs\Tab::make('Endereço')
                            ->schema([
                                Forms\Components\TextInput::make('cep')
                                    ->label('CEP')
                                    ->mask('99999-999'),
                                
                                Forms\Components\TextInput::make('endereco')
                                    ->label('Rua / Avenida')
                                    ->columnSpan(2),

                                Forms\Components\TextInput::make('numero')
                                    ->label('Número'),

                                Forms\Components\TextInput::make('complemento')
                                    ->label('Complemento')
                                    ->columnSpan(2),

                                Forms\Components\TextInput::make('bairro')
                                    ->label('Bairro'),

                                Forms\Components\TextInput::make('cidade')
                                    ->label('Cidade'),

                                Forms\Components\TextInput::make('uf')
                                    ->label('UF')
                                    ->maxLength(2),
                            ])->columns(3),
                            
                        // ABA 3: ACESSO AO SISTEMA
                        Forms\Components\Tabs\Tab::make('Acesso ao Sistema')
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

                                Forms\Components\Select::make('specialties')
                                    ->label('Especialidades Médicas')
                                    ->relationship('specialties', 'name', function($query) {
                                        return $query->where('type', 'medica');
                                    })
                                    ->multiple()
                                    ->preload()
                                    ->visible(fn (Get $get) => $get('role') === 'doctor'),
                            ])
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
                
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('role')
                    ->label('Perfil')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'Administrador',
                        'doctor' => 'Médico',
                        'receptionist' => 'Recepção',
                        'super_admin' => 'Super Admin',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'super_admin' => 'danger',
                        'doctor' => 'info',
                        'receptionist' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Filtrar por Perfil')
                    ->options([
                        'doctor' => 'Médicos',
                        'receptionist' => 'Recepcionistas',
                        'admin' => 'Administradores',
                    ]),
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
        return [
            RelationManagers\SchedulesRelationManager::class,
        ];
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