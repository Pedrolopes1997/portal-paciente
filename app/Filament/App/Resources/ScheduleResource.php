<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ScheduleResource\Pages;
use App\Models\Schedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ScheduleResource extends Resource
{

    // No ScheduleResource.php
    protected static bool $shouldRegisterNavigation = false;
    
    protected static ?string $model = Schedule::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Cadastros';
    protected static ?string $modelLabel = 'Grade de Horário';
    protected static ?string $pluralModelLabel = 'Config. de Agendas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Configuração da Grade')
                    ->schema([
                        // Seleciona o Médico
                        Forms\Components\Select::make('user_id')
                            ->label('Médico')
                            ->relationship('doctor', 'name', function ($query) {
                                return $query->where('tenant_id', auth()->user()->tenant_id)
                                             ->where('role', 'doctor');
                            })
                            ->required()
                            ->searchable()
                            ->preload(),

                        // Seleciona o Dia da Semana
                        Forms\Components\Select::make('day_of_week')
                            ->label('Dia da Semana')
                            ->options([
                                1 => 'Segunda-feira',
                                2 => 'Terça-feira',
                                3 => 'Quarta-feira',
                                4 => 'Quinta-feira',
                                5 => 'Sexta-feira',
                                6 => 'Sábado',
                                0 => 'Domingo',
                            ])
                            ->required(),

                        // Horários
                        Forms\Components\TimePicker::make('start_time')
                            ->label('Início')
                            ->required()
                            ->seconds(false),

                        Forms\Components\TimePicker::make('end_time')
                            ->label('Fim')
                            ->required()
                            ->seconds(false),
                            
                        Forms\Components\TextInput::make('duration_minutes')
                            ->label('Duração da Consulta (min)')
                            ->numeric()
                            ->default(30)
                            ->required(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('doctor.name')
                    ->label('Médico')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('day_of_week')
                    ->label('Dia')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        1 => 'Segunda', 2 => 'Terça', 3 => 'Quarta', 
                        4 => 'Quinta', 5 => 'Sexta', 6 => 'Sábado', 0 => 'Domingo',
                        default => ''
                    })
                    ->badge(),

                Tables\Columns\TextColumn::make('start_time')
                    ->label('Início')
                    ->time('H:i'),

                Tables\Columns\TextColumn::make('end_time')
                    ->label('Fim')
                    ->time('H:i'),
                    
                Tables\Columns\TextColumn::make('duration_minutes')
                    ->label('Duração')
                    ->suffix(' min'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}