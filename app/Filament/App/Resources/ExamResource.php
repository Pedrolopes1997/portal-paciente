<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ExamResource\Pages;
use App\Models\Specialty;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExamResource extends Resource
{
    // Apontamos para a tabela Specialties (onde ficam os nomes dos exames)
    protected static ?string $model = Specialty::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    protected static ?string $navigationGroup = 'Cadastros';
    protected static ?string $navigationLabel = 'Exames e Procedimentos';
    protected static ?string $modelLabel = 'Exame';

    // Filtra para mostrar APENAS o que é exame
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('type', 'exame');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome do Exame')
                            ->placeholder('Ex: Hemograma Completo')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('description')
                            ->label('Descrição / Preparo')
                            ->placeholder('Ex: Jejum de 8 horas...'),

                        // Campo Oculto para forçar ser Exame ao salvar
                        Forms\Components\Hidden::make('type')
                            ->default('exame'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Procedimento')
                    ->searchable()
                    ->sortable(), // Permite clicar para ordenar por nome
            ])
            // --- CORREÇÃO DO ERRO ---
            // Forçamos a ordenação por 'name' ou 'created_at'. 
            // Se deixasse 'date', daria o erro que você viu.
            ->defaultSort('name', 'asc') 
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
            'index' => Pages\ListExams::route('/'),
            'create' => Pages\CreateExam::route('/create'),
            'edit' => Pages\EditExam::route('/{record}/edit'),
        ];
    }
}