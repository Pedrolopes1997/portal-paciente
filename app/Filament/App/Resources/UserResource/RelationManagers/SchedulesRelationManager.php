<?php

namespace App\Filament\App\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SchedulesRelationManager extends RelationManager
{
    // O nome do relacionamento no Model User (que criamos no passo anterior)
    protected static string $relationship = 'schedules';

    protected static ?string $title = 'Configuração de Agendas';

    protected static ?string $modelLabel = 'Horário';
    
    protected static ?string $icon = 'heroicon-o-clock';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        // Só mostra essa aba se o Role for 'doctor'
        return $ownerRecord->role === 'doctor';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detalhes do Horário')
                    ->schema([
                        // --- CORREÇÃO: INJETA O ID DA CLÍNICA AUTOMATICAMENTE ---
                        Forms\Components\Hidden::make('tenant_id')
                            ->default(fn () => \Filament\Facades\Filament::getTenant()->id),
                        // --------------------------------------------------------

                        // Dia da Semana
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
                            ->label('Duração (min)')
                            ->numeric()
                            ->default(30)
                            ->required(),
                    ])->columns(2)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('day_of_week')
            ->columns([
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
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Novo Horário'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}