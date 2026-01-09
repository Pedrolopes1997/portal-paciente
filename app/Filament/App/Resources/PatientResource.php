<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\PatientResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class PatientResource extends Resource
{
    protected static ?int $navigationSort = 1;

    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Cadastros';
    protected static ?string $navigationLabel = 'Pacientes';
    protected static ?string $modelLabel = 'Paciente';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('is_patient', true);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Cadastro do Paciente')
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

                                Forms\Components\TextInput::make('cns')
                                    ->label('Cartão SUS (CNS)')
                                    ->mask('999999999999999'),

                                Forms\Components\DatePicker::make('nascimento')
                                    ->label('Data de Nascimento')
                                    ->displayFormat('d/m/Y')
                                    ->format('Y-m-d')
                                    ->native(false)
                                    ->maxDate(now()),
                                
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

                            // ABA 3: CONVÊNIO (NOVO)
                        Forms\Components\Tabs\Tab::make('Plano de Saúde')
                            ->schema([
                                Forms\Components\TextInput::make('convenio')
                                    ->label('Nome do Convênio')
                                    ->placeholder('Ex: Unimed, Bradesco Saúde'),
                                
                                Forms\Components\TextInput::make('carteirinha')
                                    ->label('Número da Carteirinha'),

                                Forms\Components\DatePicker::make('validade_carteirinha')
                                    ->label('Validade da Carteirinha')
                                    ->format('Y-m-d')
                                    ->displayFormat('d/m/Y'),
                            ])->columns(2),

                        // ABA 4: ACESSO
                        Forms\Components\Tabs\Tab::make('Acesso ao Portal')
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->label('E-mail (Login)'),

                                Forms\Components\TextInput::make('password')
                                    ->password()
                                    ->label('Senha')
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->required(fn (string $context): bool => $context === 'create'),

                                Forms\Components\Hidden::make('is_patient')
                                    ->default(true),

                                // Campo Oculto para forçar ser Paciente
                                Forms\Components\Hidden::make('role')
                                    ->default('patient'),
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
                
                Tables\Columns\TextColumn::make('cpf')
                    ->label('CPF')
                    ->searchable(),

                Tables\Columns\TextColumn::make('celular')
                    ->label('Celular'),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y')
                    ->label('Cadastrado em')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }
}