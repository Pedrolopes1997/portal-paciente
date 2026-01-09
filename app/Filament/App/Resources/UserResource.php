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

class UserResource extends Resource
{
    protected static ?int $navigationSort = 2;

    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    // CORREÇÃO DOS NOMES
    protected static ?string $modelLabel = 'Usuário';
    protected static ?string $pluralModelLabel = 'Usuários';
    
    protected static ?string $navigationGroup = 'Cadastros';
    protected static ?string $navigationLabel = 'Usuários e Médicos';

    public static function getEloquentQuery(): Builder
    {
        // Mostra tudo MENOS pacientes
        return parent::getEloquentQuery()->where('role', '!=', 'patient');
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

                                // --- CORREÇÃO: MOVIDO PARA DENTRO DA ABA ---
                                Forms\Components\Select::make('role')
                                    ->label('Perfil de Acesso')
                                    ->options([
                                        'admin' => 'Admin',
                                        'doctor' => 'Médico',
                                        'receptionist' => 'Recepção'
                                    ])
                                    ->required()
                                    ->live(), // Importante para o visible do CRM

                                Forms\Components\TextInput::make('crm')
                                    ->label('CRM / Registro')
                                    ->visible(fn (Forms\Get $get) => $get('role') === 'doctor'),
                                // -------------------------------------------
                            ])->columns(2),

                        // ABA 2: ENDEREÇO (Opcional para staff, mas mantive)
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
                            
                        // ABA 3: ACESSO
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

                                // Especialidades (Exclusivo para médicos)
                                Forms\Components\Select::make('specialties')
                                    ->label('Especialidades Médicas')
                                    ->relationship('specialties', 'name', function($query) {
                                        return $query->where('type', 'medica');
                                    })
                                    ->multiple()
                                    ->preload()
                                    ->visible(fn (Forms\Get $get) => $get('role') === 'doctor'),
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
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
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