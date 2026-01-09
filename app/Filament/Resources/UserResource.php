<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Tabs;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Hash;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Get; // Importante para pegar valor dinâmico

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Gestão Corporativa';
    protected static ?string $navigationLabel = 'Usuários do Sistema';
    protected static ?string $modelLabel = 'Usuário';
    protected static ?string $pluralModelLabel = 'Usuários';
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Dados do Usuário')
                    ->tabs([
                        // --- ABA 1: ACESSO E PERMISSÕES ---
                        Tabs\Tab::make('Acesso e Sistema')
                            ->icon('heroicon-o-key')
                            ->schema([
                                Select::make('role')
                                    ->label('Função / Permissão')
                                    ->options([
                                        'super_admin' => 'Super Admin (Dono)',
                                        'admin_clinica' => 'Admin da Clínica',
                                        'doctor' => 'Médico',
                                        'receptionist' => 'Recepção',
                                        'paciente' => 'Paciente',
                                    ])
                                    ->default('paciente')
                                    ->live() // --- MUDANÇA: Torna o campo reativo ---
                                    ->required(),

                                Select::make('tenant_id')
                                    ->relationship('tenant', 'name')
                                    ->label('Clínica Vinculada')
                                    ->searchable()
                                    ->preload()
                                    // --- MUDANÇA: Só é obrigatório se NÃO for Super Admin ---
                                    ->required(fn (Get $get) => $get('role') !== 'super_admin')
                                    ->helperText(fn (Get $get) => $get('role') === 'super_admin' ? 'Super Admins têm acesso a todas as clínicas, vínculo opcional.' : ''),

                                TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->label('E-mail de Login'),

                                TextInput::make('password')
                                    ->password()
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->label('Senha'),
                            ])->columns(2),

                        // --- ABA 2: DADOS PESSOAIS ---
                        Tabs\Tab::make('Dados Pessoais')
                            ->icon('heroicon-o-user')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->label('Nome Completo')
                                    ->columnSpanFull(),

                                TextInput::make('cpf')
                                    ->label('CPF')
                                    ->mask('999.999.999-99'),

                                TextInput::make('cns')
                                    ->label('Cartão SUS (CNS)')
                                    ->mask('999999999999999'),

                                DatePicker::make('nascimento')
                                    ->label('Data de Nascimento')
                                    ->displayFormat('d/m/Y')
                                    ->format('Y-m-d')
                                    ->native(false),
                                
                                TextInput::make('nome_mae')
                                    ->label('Nome da Mãe'),
                                
                                TextInput::make('celular')
                                    ->label('Celular / WhatsApp')
                                    ->mask('(99) 99999-9999'),
                            ])->columns(2),

                        // --- ABA 3: ENDEREÇO ---
                        Tabs\Tab::make('Endereço')
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                TextInput::make('cep')
                                    ->label('CEP')
                                    ->mask('99999-999')
                                    ->columnSpan(1),
                                
                                TextInput::make('endereco')
                                    ->label('Rua / Avenida')
                                    ->columnSpan(2),

                                TextInput::make('numero')
                                    ->label('Número')
                                    ->columnSpan(1),

                                TextInput::make('complemento')
                                    ->label('Complemento')
                                    ->placeholder('Ex: Ap 102')
                                    ->columnSpan(2),

                                TextInput::make('bairro')
                                    ->label('Bairro')
                                    ->columnSpan(1),

                                TextInput::make('cidade')
                                    ->label('Cidade')
                                    ->columnSpan(1),

                                TextInput::make('uf')
                                    ->label('UF')
                                    ->maxLength(2)
                                    ->columnSpan(1),
                            ])->columns(3),
                    ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tenant.name')
                    ->label('Clínica')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->default('Acesso Global'), // Mostra isso se for NULL

                TextColumn::make('role')
                    ->label('Função')
                    ->badge()
                    ->colors([
                        'danger' => 'super_admin',
                        'warning' => 'admin_clinica',
                        'success' => 'paciente',
                        'info' => 'doctor',
                    ]),
                
                TextColumn::make('cpf')
                    ->label('CPF')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('email')
                    ->searchable(),
                
                TextColumn::make('created_at')
                    ->date('d/m/Y')
                    ->label('Cadastro'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tenant')
                    ->relationship('tenant', 'name')
                    ->label('Filtrar por Clínica'),
                
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'super_admin' => 'Super Admin',
                        'admin_clinica' => 'Admin Clínica',
                        'doctor' => 'Médico',
                        'receptionist' => 'Recepção',
                        'paciente' => 'Paciente',
                    ])
                    ->label('Filtrar por Função'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Impersonate::make()
                    ->label('Acessar')
                    ->redirectTo(fn ($record) => route('paciente.dashboard', ['tenant_slug' => $record->tenant->slug ?? 'default'])),
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

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'E-mail' => $record->email,
            'Hospital' => $record->tenant->name ?? 'Global',
        ];
    }
    
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        // FILTRO RESTRITIVO:
        // Mostra apenas quem tem cargo de gestão (Super Admin ou Admin de Clínica)
        // Ignora: Pacientes, Médicos e Recepcionistas.
        return parent::getEloquentQuery()
            ->whereIn('role', ['super_admin', 'admin', 'admin_clinica']);
    }
}