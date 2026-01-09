<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\AppointmentResource\Pages;
use App\Models\Appointment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    
    // NOME DEFINITIVO
    protected static ?string $modelLabel = 'Agendamento';
    protected static ?string $pluralModelLabel = 'Agenda';
    protected static ?string $navigationLabel = 'Agenda';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // SEÇÃO 1: CONTEXTO
                Forms\Components\Section::make('O que vamos agendar?')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('Tipo de Agenda')
                            ->options([
                                'consulta' => 'Consulta Médica',
                                'exame' => 'Exame / Procedimento',
                            ])
                            ->default('consulta')
                            ->required()
                            ->live() 
                            ->native(false)
                            ->columnSpan(1),

                        Forms\Components\DateTimePicker::make('scheduled_at')
                            ->label('Data e Horário')
                            ->seconds(false)
                            ->required()
                            ->native(false)
                            ->columnSpan(1),
                    ])->columns(2),

                // SEÇÃO 2: DETALHES (Muda conforme o tipo)
                Forms\Components\Section::make(fn (Forms\Get $get) => $get('type') === 'exame' ? 'Dados do Exame' : 'Dados da Consulta')
                    ->schema([
                        // CONSULTA: Pede Médico
                        Forms\Components\Select::make('doctor_id')
                            ->label('Selecione o Médico')
                            ->relationship('doctor', 'name', function ($query) {
                                return $query->where('tenant_id', auth()->user()->tenant_id)->where('role', 'doctor');
                            })
                            ->searchable()
                            ->preload()
                            ->live()
                            ->visible(fn (Forms\Get $get) => $get('type') !== 'exame')
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => 
                                $set('medico', \App\Models\User::find($state)?->name)
                            ),

                        // EXAME: Nome do Exame
                        Forms\Components\TextInput::make('medico')
                            ->label(fn (Forms\Get $get) => $get('type') === 'exame' ? 'Nome do Procedimento' : 'Nome de Exibição')
                            ->placeholder(fn (Forms\Get $get) => $get('type') === 'exame' ? 'Ex: Raio-X de Tórax' : '')
                            ->required()
                            ->visible(fn (Forms\Get $get) => $get('type') === 'exame' || !$get('doctor_id')),

                        // AMBOS: Categoria/Especialidade
                        Forms\Components\Select::make('specialty_id')
                            ->label(fn (Forms\Get $get) => $get('type') === 'exame' ? 'Categoria do Exame' : 'Especialidade')
                            ->relationship('specialty', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')->required()->label('Nome'),
                            ]),
                    ])->columns(2),

                // SEÇÃO 3: PACIENTE
                Forms\Components\Section::make('Paciente')
                    ->schema([
                        Forms\Components\Select::make('patient_id')
                            ->relationship('patient', 'name', function ($query) {
                                return $query->where('tenant_id', auth()->user()->tenant_id);
                            })
                            ->label('Selecione o Paciente')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')->required()->label('Nome'),
                                Forms\Components\TextInput::make('celular')->label('Celular'),
                            ]),
                        
                        Forms\Components\Select::make('status')
                            ->options([
                                'agendado' => 'Agendado',
                                'confirmado' => 'Confirmado',
                                'realizado' => 'Realizado',
                                'cancelado' => 'Cancelado',
                            ])
                            ->default('agendado')
                            ->required(),

                        Forms\Components\Textarea::make('patient_notes')
                            ->label('Observações')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup('scheduled_at')
            ->groups([
                Tables\Grouping\Group::make('scheduled_at')->label('Data')->date()->collapsible(),
            ])
            ->defaultSort('scheduled_at', 'asc')
            ->columns([
                
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label('Horário')
                    ->dateTime('H:i')
                    ->weight('bold')
                    ->size(Tables\Columns\TextColumn\TextColumnSize::Large),

                Tables\Columns\TextColumn::make('display_info')
                    ->label('Agenda de')
                    ->state(function (Appointment $record) {
                        return $record->type === 'exame' 
                            ? ($record->getRawOriginal('medico') ?? 'Exame')
                            : ($record->doctor->name ?? $record->getRawOriginal('medico'));
                    })
                    ->description(fn (Appointment $record) => $record->specialty?->name)
                    ->icon(fn (Appointment $record) => $record->type === 'exame' ? 'heroicon-m-beaker' : 'heroicon-m-user-circle')
                    ->color(fn (Appointment $record) => $record->type === 'exame' ? 'purple' : 'info'),

                Tables\Columns\TextColumn::make('patient.name')
                    ->label('Paciente')
                    ->searchable()
                    ->weight('medium')
                    ->description(fn (Appointment $record) => $record->patient->celular ?? ''),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pendente',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'agendado', 'pending' => 'gray',
                        'confirmado' => 'success',
                        'realizado' => 'info',
                        'cancelado' => 'danger',
                        default => 'warning',
                    }),
            ])
            // FILTROS LIMPOS (No menu lateral, não poluindo o topo)
            ->filters([
                
                // 1. DATA (Principal)
                Tables\Filters\Filter::make('scheduled_at')
                    ->form([
                        Forms\Components\DatePicker::make('data_especifica')
                            ->label('Data do Agendamento')
                            ->default(now()), // Opcional: Já vem filtrado hoje se quiser
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['data_especifica'],
                                fn (Builder $query, $date): Builder => $query->whereDate('scheduled_at', $date),
                            );
                    }),

                // 2. STATUS
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'agendado' => 'Agendado',
                        'confirmado' => 'Confirmado',
                        'realizado' => 'Realizado',
                        'cancelado' => 'Cancelado',
                    ]),

                // 3. MÉDICO (Oculto visualmente se já estiver filtrado, mas necessário existir)
                Tables\Filters\SelectFilter::make('doctor_id')
                    ->label('Médico')
                    ->relationship('doctor', 'name', function ($query) {
                         return $query->where('tenant_id', auth()->user()->tenant_id);
                    })
                    ->searchable()
                    ->preload(),

                // 4. EXAME
                Tables\Filters\SelectFilter::make('specialty_id')
                    ->label('Procedimento')
                    ->relationship('specialty', 'name')
                    ->searchable()
                    ->preload(),

            ]) // SEM 'layout: AboveContent' -> Volta para o padrão limpo
            
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('whatsapp')
                        ->label('Zap')
                        ->icon('heroicon-o-chat-bubble-left-right')
                        ->color('success')
                        ->url(fn ($record) => 'https://wa.me/' . preg_replace('/\D/', '', $record->patient->celular ?? '') . '?text=' . urlencode('Olá, confirmamos seu horário?'), true),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([ Tables\Actions\DeleteBulkAction::make() ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            // Rota raiz '/' agora é a nossa SELEÇÃO (AgendaSelection)
            'index' => Pages\AgendaSelection::route('/'),
            
            // Movemos a listagem original (Tabela) para '/list'
            'list' => Pages\ListAppointments::route('/list'),
            
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}