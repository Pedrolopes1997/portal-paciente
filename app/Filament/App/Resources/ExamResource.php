<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ExamResource\Pages;
use App\Filament\App\Resources\ExamResource\RelationManagers;
use App\Models\Exam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

        protected static ?string $modelLabel = 'Exame';
    protected static ?string $pluralModelLabel = 'Exames';
    protected static ?string $navigationLabel = 'Exames';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Paciente')
                    ->relationship('user', 'name') // Busca relação user e mostra o nome
                    ->searchable()
                    ->preload()
                    ->required(),
                
                Forms\Components\TextInput::make('title')
                    ->label('Nome do Exame')
                    ->placeholder('Ex: Hemograma Completo')
                    ->required(),

                Forms\Components\DatePicker::make('date')
                    ->label('Data do Exame')
                    ->default(now())
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'analise' => 'Em Análise',
                        'liberado' => 'Liberado',
                    ])
                    ->default('analise')
                    ->required(),

                Forms\Components\FileUpload::make('file_path')
                    ->label('Arquivo PDF')
                    ->directory('exames_locais') // Pasta no storage
                    ->acceptedFileTypes(['application/pdf'])
                    ->preserveFilenames(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Paciente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Exame')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->date('d/m/Y')
                    ->label('Data'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'analise',
                        'success' => 'liberado',
                    ]),
            ])
            ->defaultSort('date', 'desc');
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
            'index' => Pages\ListExams::route('/'),
            'create' => Pages\CreateExam::route('/create'),
            'edit' => Pages\EditExam::route('/{record}/edit'),
        ];
    }
}
