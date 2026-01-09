<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\SpecialtyResource\Pages;
use App\Models\Specialty;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MedicalSpecialtyResource extends Resource
{
    protected static ?string $model = Specialty::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    
    // Agrupa no menu lateral
    protected static ?string $navigationGroup = 'Cadastros';
    
    protected static ?string $modelLabel = 'Especialidade / Exame';
    protected static ?string $pluralModelLabel = 'Especialidades e Exames';
    protected static ?string $navigationLabel = 'Especialidades Médicas';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('type', 'medica');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->placeholder('Ex: Cardiologia ou Hemograma')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('type')
                            ->label('Tipo de Cadastro')
                            ->options([
                                'medica' => 'Especialidade Médica (Consulta)',
                                'exame' => 'Procedimento / Exame',
                            ])
                            ->default('medica')
                            ->required()
                            ->native(false),
                            
                        Forms\Components\Textarea::make('description')
                            ->label('Descrição (Opcional)')
                            ->columnSpanFull(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'medica' => 'Consulta',
                        'exame' => 'Exame',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'medica' => 'info',
                        'exame' => 'purple',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Filtrar por Tipo')
                    ->options([
                        'medica' => 'Consultas',
                        'exame' => 'Exames',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSpecialties::route('/'),
            'create' => Pages\CreateSpecialty::route('/create'),
            'edit' => Pages\EditSpecialty::route('/{record}/edit'),
        ];
    }
}