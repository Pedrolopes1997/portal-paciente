<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\AppointmentResource\Pages;
use App\Filament\App\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    
    protected static ?string $modelLabel = 'Agendamento';
    protected static ?string $pluralModelLabel = 'Agendamentos';
    protected static ?string $navigationLabel = 'Agendamentos';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            // Card de Identificação
            Forms\Components\Section::make('Detalhes da Consulta')
                ->schema([
                    // Selecionar Paciente (Filtra apenas pacientes desta clínica)
                    Forms\Components\Select::make('user_id')
                        ->relationship('user', 'name', function ($query) {
                            // Garante que só apareçam pacientes do mesmo tenant
                            return $query->where('tenant_id', auth()->user()->tenant_id);
                        })
                        ->label('Paciente')
                        ->searchable()
                        ->preload()
                        ->required(),

                    // Data e Hora
                    Forms\Components\DateTimePicker::make('data_agendamento')
                        ->label('Data e Hora')
                        ->seconds(false) // Remove segundos
                        ->required()
                        ->native(false), // Usa o calendário bonitinho do Filament
                ])->columns(2),

            // Card Profissional
            Forms\Components\Section::make('Profissional')
                ->schema([
                    Forms\Components\TextInput::make('medico')
                        ->label('Médico(a)')
                        ->required(),
                    
                    Forms\Components\TextInput::make('especialidade')
                        ->label('Especialidade')
                        ->placeholder('Ex: Cardiologista'),
                        
                    Forms\Components\Select::make('status')
                        ->options([
                            'agendado' => 'Agendado',
                            'confirmado' => 'Confirmado',
                            'realizado' => 'Realizado',
                            'cancelado' => 'Cancelado',
                        ])
                        ->default('agendado')
                        ->required(),
                ])->columns(3),
                
            Forms\Components\Textarea::make('observacoes')
                ->label('Observações')
                ->columnSpanFull(),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('user.name')
                ->label('Paciente')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('medico')
                ->label('Médico')
                ->searchable(),

            Tables\Columns\TextColumn::make('data_agendamento')
                ->label('Data')
                ->dateTime('d/m/Y H:i') // Formato Brasileiro
                ->sortable(),

            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'agendado' => 'gray',
                    'confirmado' => 'info',
                    'realizado' => 'success',
                    'cancelado' => 'danger',
                }),
        ])
        ->filters([
            // Filtro por Status
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'agendado' => 'Agendado',
                    'confirmado' => 'Confirmado',
                    'realizado' => 'Realizado',
                    'cancelado' => 'Cancelado',
                ]),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
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
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
