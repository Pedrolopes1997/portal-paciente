<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ExamResultResource\Pages;
use App\Models\Exam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder; // Importante para evitar aquele erro de compatibilidade

class ExamResultResource extends Resource
{
    protected static ?string $model = Exam::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-up';
    
    // Colocamos num grupo separado para não misturar com os Cadastros
    protected static ?string $navigationGroup = 'Atendimento';
    
    protected static ?string $navigationLabel = 'Resultados de Exames';
    protected static ?string $modelLabel = 'Resultado de Exame';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Dados do Exame')
                    ->schema([
                        // Seleciona o Paciente (Usuário)
                        // Filtramos para trazer apenas quem tem role = 'patient' para facilitar
                        Forms\Components\Select::make('user_id')
                            ->label('Paciente')
                            ->relationship('user', 'name', function (Builder $query) {
                                return $query->where('role', 'patient');
                            })
                            ->searchable()
                            ->preload()
                            ->required(),

                        // Título (Ex: Hemograma, Raio-X)
                        Forms\Components\TextInput::make('title')
                            ->label('Título do Exame')
                            ->placeholder('Ex: Hemograma Completo')
                            ->required()
                            ->maxLength(255),

                        // Data
                        Forms\Components\DatePicker::make('date')
                            ->label('Data de Realização')
                            ->required()
                            ->default(now()),

                        // Status
                        Forms\Components\Select::make('status')
                            ->options([
                                'pendente' => 'Pendente / Em Análise',
                                'liberado' => 'Liberado para o Paciente',
                            ])
                            ->default('pendente')
                            ->required(),

                        // Upload do PDF
                        Forms\Components\FileUpload::make('file_path')
                            ->label('Arquivo do Laudo (PDF)')
                            ->directory('exams-results') // Pasta onde salva no storage
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->preserveFilenames()
                            ->columnSpanFull(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Nome do Paciente
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Paciente')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Exame')
                    ->searchable(),

                Tables\Columns\TextColumn::make('date')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match (strtolower($state)) {
                        'pendente' => 'Pendente',
                        'liberado' => 'Liberado',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'pendente' => 'warning',
                        'liberado' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('date', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                
                // Botão de Download na listagem
                Tables\Actions\Action::make('download')
                    ->label('Baixar')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (Exam $record) => $record->file_path ? \Illuminate\Support\Facades\Storage::url($record->file_path) : '#')
                    ->openUrlInNewTab()
                    ->visible(fn (Exam $record) => !empty($record->file_path)),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExamResults::route('/'),
            'create' => Pages\CreateExamResult::route('/create'),
            'edit' => Pages\EditExamResult::route('/{record}/edit'),
        ];
    }
}