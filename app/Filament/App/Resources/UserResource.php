<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\UserResource\Pages;
use App\Filament\App\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Paciente';
    protected static ?string $pluralModelLabel = 'Pacientes';
    protected static ?string $navigationLabel = 'Pacientes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome Completo')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(table: 'users', column: 'email', ignoreRecord: true) 
                    ->label('E-mail'),
                Forms\Components\TextInput::make('cpf')
                    ->label('CPF')
                    ->mask('999.999.999-99'), // Opcional: Instalar pacote de máscara depois
                Forms\Components\DatePicker::make('nascimento') // Se tiver criado campo data_nascimento
                    ->label('Data de Nascimento'),
                // Ocultamos a senha na criação e geramos uma padrão ou enviamos por email
                // Para simplificar agora, vamos pedir uma senha manual
                Forms\Components\TextInput::make('password')
                    ->label('Senha Inicial')
                    ->password()
                    ->required(fn ($livewire) => $livewire instanceof CreateRecord) // Só obriga na criação
                    ->dehydrated(fn ($state) => filled($state)), // Só salva se preencher
                
                // Campos Ocultos Automáticos
                Forms\Components\Hidden::make('role')->default('paciente'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nome')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('E-mail'),
                Tables\Columns\TextColumn::make('created_at')->label('Cadastrado em')->date('d/m/Y'),
            ])
            ->filters([
                // Filtro para mostrar apenas pacientes
                Tables\Filters\Filter::make('Apenas Pacientes')
                    ->query(fn ($query) => $query->where('role', 'paciente'))
                    ->default(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
}
