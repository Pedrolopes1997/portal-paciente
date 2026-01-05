<div class="min-h-screen bg-gray-50 pb-12">
    
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    👋 Olá, {{ explode(' ', $paciente['nome'])[0] }}
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Bem-vindo ao portal <strong>{{ $tenant->name }}</strong>.
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-8">
            
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border-l-4 border-indigo-500 p-5 flex items-center justify-between transition hover:shadow-md">
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Carteirinha</p>
                    <p class="text-lg font-bold text-gray-800">{{ $paciente['carteirinha'] }}</p>
                </div>
                <div class="p-3 bg-indigo-50 rounded-full text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border-l-4 border-teal-500 p-5 flex items-center justify-between transition hover:shadow-md">
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Convênio</p>
                    <p class="text-lg font-bold text-gray-800">{{ $paciente['plano'] }}</p>
                </div>
                <div class="p-3 bg-teal-50 rounded-full text-teal-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border-l-4 border-purple-500 p-5 flex items-center justify-between transition hover:shadow-md">
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Último Exame</p>
                    <p class="text-lg font-bold text-gray-800">
                        @if($exames->isNotEmpty())
                            {{ $exames->first()['data'] }}
                        @else
                            --/--/--
                        @endif
                    </p>
                </div>
                <div class="p-3 bg-purple-50 rounded-full text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <section>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Próximos Agendamentos
                    </h3>
                    @if($tenant->whatsapp)
                        <a href="{{ $tenant->getWhatsappLink('Olá, gostaria de agendar uma consulta.') }}" target="_blank" ...>Novo +</a>
                    @endif
                </div>

                @if($agendamentos->isEmpty())
                    <div class="bg-white rounded-xl shadow-sm p-8 text-center border border-gray-100">
                        <div class="text-gray-300 mb-3">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-gray-500 font-medium">Sem agendamentos futuros.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($agendamentos as $agenda)
                            <div class="bg-white rounded-xl shadow-sm border-l-4 {{ $agenda->status == 'confirmado' ? 'border-green-500' : 'border-blue-500' }} p-5 hover:shadow-md transition duration-200">
                                <div class="flex justify-between items-start">
                                    <div class="flex flex-col items-center justify-center bg-gray-50 rounded-lg p-3 min-w-[70px] border border-gray-100">
                                        <span class="text-xs font-bold text-gray-400 uppercase">{{ \Carbon\Carbon::parse($agenda->data_agendamento)->format('M') }}</span>
                                        <span class="text-2xl font-bold text-gray-800">{{ \Carbon\Carbon::parse($agenda->data_agendamento)->format('d') }}</span>
                                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($agenda->data_agendamento)->format('H:i') }}</span>
                                    </div>

                                    <div class="flex-1 ml-4">
                                        <h4 class="font-bold text-gray-800 text-lg">{{ $agenda->medico }}</h4>
                                        <p class="text-sm text-gray-500 mb-2">{{ $agenda->especialidade ?? 'Consulta' }}</p>
                                        
                                        @if($agenda->status == 'confirmado')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Confirmado</span>
                                        @elseif($agenda->status == 'cancelado')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Cancelado</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Agendado</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <section>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Últimos Resultados
                    </h3>
                    <a href="{{ route('paciente.exames', ['tenant_slug' => $tenant->slug]) }}" class="text-sm text-indigo-600 hover:underline">Ver todos</a>
                </div>

                @if($exames->isEmpty())
                    <div class="bg-white rounded-xl shadow-sm p-8 text-center border border-gray-100">
                        <div class="text-gray-300 mb-3">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <p class="text-gray-500 font-medium">Nenhum exame encontrado.</p>
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 divide-y divide-gray-100">
                        @foreach($exames as $exame)
                            <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0">
                                        @if($exame['is_liberado'])
                                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600" title="Liberado">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600" title="Em Análise">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-800 text-sm truncate pr-2">{{ \Illuminate\Support\Str::limit($exame['descricao'], 30) }}</p>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <p class="text-xs text-gray-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                {{ $exame['data'] }}
                                            </p>
                                            <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-sm {{ $exame['is_liberado'] ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' }}">
                                                {{ $exame['status'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    @if(isset($exame['link_laudo']) && $exame['link_laudo'] != '#')
                                        <a href="{{ $exame['link_laudo'] }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-xs font-bold uppercase">
                                            PDF
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>
    </div>
</div>