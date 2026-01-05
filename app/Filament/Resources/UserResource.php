<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;     // <--- Faltava
use Filament\Forms\Components\TextInput;  // <--- Faltava
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;   // <--- Faltava e causou o erro
use Illuminate\Support\Facades\Hash;      // <--- Faltava para a senha
use STS\FilamentImpersonate\Tables\Actions\Impersonate;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Gestão Corporativa';
    protected static ?string $navigationLabel = 'Usuários do Sistema';
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Forms\Form $form): Forms\Form
{
    return $form
        ->schema([
            Select::make('tenant_id')
                ->relationship('tenant', 'name')
                ->label('Clínica Vinculada')
                ->required(),

            TextInput::make('name')
                ->required()
                ->label('Nome Completo'),

            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                // Adicione esta validação para impedir o erro de tela (crash)
                ->unique(ignoreRecord: true)
                ->label('E-mail'),

            // Campo de senha que só atualiza se for preenchido
            Forms\Components\TextInput::make('password')
                ->password()
                // Isso garante que, ao salvar, a senha seja criptografada
                ->dehydrateStateUsing(fn ($state) => \Illuminate\Support\Facades\Hash::make($state))
                ->dehydrated(fn ($state) => filled($state)) // Só atualiza se o campo não estiver vazio
                ->required(fn (string $context): bool => $context === 'create') // Obrigatório só na criação
                ->label('Senha'),
                
            Select::make('role')
                ->options([
                    'super_admin' => 'Super Admin (Dono)',
                    'admin_clinica' => 'Admin da Clínica',
                    'paciente' => 'Paciente',
                ])
                ->required(),
        ]);
}

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('name')->searchable()->sortable(),
            TextColumn::make('email')->searchable(),
            
            TextColumn::make('tenant.name')
                ->label('Clínica')
                ->badge()
                ->color('gray')
                ->sortable(),

            TextColumn::make('role')
                ->badge()
                ->colors([
                    'danger' => 'super_admin',
                    'warning' => 'admin_clinica',
                    'success' => 'paciente',
                ]),
                
            TextColumn::make('created_at')->date()->label('Cadastro'),
        ])
        ->filters([
            // Filtro para ver usuários de uma clínica específica
            Tables\Filters\SelectFilter::make('tenant')
                ->relationship('tenant', 'name')
                ->label('Filtrar por Clínica'),
        ])

        ->actions([
              Tables\Actions\EditAction::make(),

            // O botão mágico
                Impersonate::make()
                    ->label('Acessar')
                    ->tooltip('Logar como este usuário no Portal')
                    // CORREÇÃO AQUI: mudamos de 'dashboard' para 'paciente.dashboard'
                    ->redirectTo(fn ($record) => route('paciente.dashboard', ['tenant_slug' => $record->tenant->slug])),
            ]);
}

    public static function getRelations(): array
    {
        return [
            //
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

    // Opcional: Mostrar mais detalhes na busca (ex: email e hospital)
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'E-mail' => $record->email,
            'Hospital' => $record->tenant->name,
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['tenant']);
    }
}