<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TenantResource\Pages;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str; // Necessário para gerar o slug automático

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2'; // Mudei o ícone para um prédio (Clínica)
    protected static ?string $navigationGroup = 'Gestão Corporativa';
    protected static ?string $navigationLabel = 'Clínicas & Hospitais';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'Clínica';
    protected static ?string $pluralModelLabel = 'Clínicas & Hospitais';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // --- COLUNA DA ESQUERDA (PRINCIPAL - 2/3) ---
                Forms\Components\Group::make()
                    ->schema([
                        
                        // 1. Bloco de Identidade
                        Forms\Components\Section::make('Identidade Visual')
                            ->description('Dados públicos e aparência do portal')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nome da Clínica')
                                    ->required()
                                    ->live(onBlur: true) // Ao sair do campo...
                                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                        // ...Se estiver criando, gera o slug automático
                                        if ($operation === 'create') {
                                            $set('slug', Str::slug($state));
                                        }
                                    })
                                    ->columnSpanFull(),

                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('whatsapp')
                                        ->label('WhatsApp (Suporte)')
                                        ->placeholder('5511999999999')
                                        ->mask('9999999999999') // Máscara simples
                                        ->tel()
                                        ->maxLength(20),

                                    Forms\Components\ColorPicker::make('primary_color')
                                        ->label('Cor Principal')
                                        ->default('#0ea5e9'), // Azul padrão
                                ]),

                                Forms\Components\FileUpload::make('logo_path')
                                    ->label('Logomarca (Recomendado: PNG Transparente)')
                                    ->image()
                                    ->directory('tenants/logos') // Pasta correta!
                                    ->visibility('public')
                                    ->imageEditor() // Permite cortar a imagem na hora
                                    ->columnSpanFull(),
                            ]),

                        // 2. Bloco Técnico (ERP e Banco)
                        Forms\Components\Section::make('Integração Técnica')
                            ->description('Configuração de conexão com o sistema hospitalar')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\Select::make('mode')
                                        ->label('Modo de Operação')
                                        ->options([
                                            'standalone' => 'Portal Próprio (SaaS)',
                                            'integrated' => 'Integrado (ERP)',
                                        ])
                                        ->live() // Reage instantaneamente
                                        ->required()
                                        ->default('standalone'),

                                    Forms\Components\Select::make('erp_driver')
                                        ->label('Sistema ERP')
                                        ->options([
                                            'tasy' => 'Philips Tasy',
                                            'mv' => 'MV Soul',
                                        ])
                                        ->visible(fn (Forms\Get $get) => $get('mode') === 'integrated')
                                        ->required(fn (Forms\Get $get) => $get('mode') === 'integrated'),
                                ]),

                                // Dados da Conexão (Só aparece se for Integrado)
                                Forms\Components\Grid::make(2)
                                    ->visible(fn (Forms\Get $get) => $get('mode') === 'integrated')
                                    ->schema([
                                        Forms\Components\TextInput::make('db_connection_data.db_host')
                                            ->label('Host / IP do Banco')
                                            ->required(),
                                        
                                        Forms\Components\TextInput::make('db_connection_data.db_port')
                                            ->label('Porta')
                                            ->default('1521')
                                            ->numeric(),
                                        
                                        Forms\Components\TextInput::make('db_connection_data.db_database')
                                            ->label('Service Name (SID) / DB Name')
                                            ->required(),
                                        
                                        Forms\Components\TextInput::make('db_connection_data.db_username')
                                            ->label('Usuário do Banco')
                                            ->required(),
                                        
                                        Forms\Components\TextInput::make('db_connection_data.db_password')
                                            ->label('Senha do Banco')
                                            ->password()
                                            ->revealable() // Permite ver a senha clicando no olho
                                            ->required(),
                                    ]),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                // --- COLUNA DA DIREITA (LATERAL - 1/3) ---
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Publicação')
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Cliente Ativo')
                                    ->helperText('Desligar bloqueia o acesso.')
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->default(true),

                                Forms\Components\TextInput::make('slug')
                                    ->label('URL do Portal')
                                    ->prefix(url('/') . '/')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/') // Só letras minusculas e hifen
                                    ->helperText('Use apenas letras minúsculas e traços.'),

                                Forms\Components\TextInput::make('domain')
                                    ->label('Domínio Personalizado')
                                    ->placeholder('portal.cliente.com')
                                    ->unique(ignoreRecord: true),
                            ]),

                        Forms\Components\Section::make('Vigência')
                            ->schema([
                                Forms\Components\DatePicker::make('subscription_start')
                                    ->label('Início do Contrato')
                                    ->default(now()),
                                
                                Forms\Components\DatePicker::make('subscription_end')
                                    ->label('Vencimento')
                                    ->native(false), // Usa o calendário bonitinho do JS
                            ]),
                            
                        Forms\Components\Section::make('Sistema')
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Criado em')
                                    ->content(fn ($record) => $record?->created_at?->format('d/m/Y H:i') ?? '-'),

                                Forms\Components\Placeholder::make('updated_at')
                                    ->label('Atualizado em')
                                    ->content(fn ($record) => $record?->updated_at?->diffForHumans() ?? '-'),
                            ]),   
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->height(40)
                    ->extraImgAttributes(['style' => 'object-fit: contain']),

                Tables\Columns\TextColumn::make('name')
                    ->label('Clínica')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Tenant $record) => $record->domain ?? 'Sem domínio próprio'),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Acessar Portal')
                    ->icon('heroicon-m-arrow-top-right-on-square') // Ícone de "Sair/Link Externo"
                    ->color('primary')
                    ->url(fn (Tenant $record) => route('paciente.login', ['tenant_slug' => $record->slug]), true) // O 'true' força abrir em nova aba
                    ->formatStateUsing(fn ($state) => '/' . $state), // Mostra visualmente como /slug

                Tables\Columns\TextColumn::make('mode')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state, Tenant $record) => match ($state) {
                        'standalone' => 'SaaS Próprio',
                        'integrated' => 'Integrado (' . strtoupper($record->erp_driver ?? '?') . ')',
                        default => $state,
                    })
                    ->colors([
                        'info' => 'standalone',
                        'success' => 'integrated',
                    ]),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Ativo'),

                Tables\Columns\TextColumn::make('subscription_end')
                    ->label('Vencimento')
                    ->date('d/m/Y')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state < now() ? 'danger' : 'success'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('mode')
                    ->options([
                        'integrated' => 'Integrado',
                        'standalone' => 'SaaS',
                    ]),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}