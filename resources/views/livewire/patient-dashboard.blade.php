<div class="pb-12">
    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
                        Olá, <span class="text-primary-600">{{ explode(' ', $paciente['nome'])[0] }}</span> 👋
                    </h1>
                    <p class="mt-2 text-gray-500">
                        Bem-vindo ao seu portal de saúde <strong>{{ $tenant->name }}</strong>.
                    </p>
                </div>
                
                <div class="flex shrink-0">
                    <a href="{{ route('paciente.agendar', ['tenant_slug' => $tenant->slug]) }}" 
                       class="inline-flex items-center justify-center w-full md:w-auto px-6 py-3 border border-transparent text-sm font-bold rounded-xl text-white bg-primary-600 hover:bg-primary-700 shadow-lg shadow-primary-500/30 transition-all transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Agendar Consulta ou Exame
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 space-y-8">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-6 shadow-soft border border-gray-100 flex items-center gap-4 transition hover:shadow-md">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .883-.392 1.75-1 2.332V11m3-2.332V11m0 0l-1.5 1.5M10 11l1.5 1.5M19 11h-2m-3-4h-2"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Carteirinha</p>
                    <p class="text-lg font-bold text-gray-900">{{ $paciente['carteirinha'] ?? '---' }}</p>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-soft border border-gray-100 flex items-center gap-4 transition hover:shadow-md">
                <div class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Convênio</p>
                    <p class="text-lg font-bold text-gray-900">{{ $paciente['plano'] ?? 'Particular' }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-soft border border-gray-100 flex items-center gap-4 transition hover:shadow-md">
                <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Último Exame</p>
                    <p class="text-lg font-bold text-gray-900">
                        @if($exames->isNotEmpty())
                            {{-- Pega o primeiro independente se for array ou objeto --}}
                            {{ is_array($exames->first()) ? $exames->first()['data'] : $exames->first()->data }}
                        @else
                            --/--/--
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-2 h-8 bg-primary-600 rounded-full mr-2"></span>
                        Próximos Agendamentos
                    </h3>
                </div>

                @if($agendamentos->isEmpty())
                    <div class="bg-white rounded-2xl shadow-sm p-10 text-center border border-dashed border-gray-300 flex flex-col items-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h4 class="text-gray-900 font-bold mb-1">Nenhum agendamento futuro</h4>
                        <p class="text-gray-500 text-sm mb-6 max-w-xs mx-auto">Não encontramos consultas ou exames agendados para os próximos dias.</p>
                        <a href="{{ route('paciente.agendar', ['tenant_slug' => $tenant->slug]) }}" class="text-primary-600 font-bold hover:text-primary-700 text-sm hover:underline">
                            + Fazer novo agendamento
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($agendamentos as $agenda)
                            <div class="group bg-white rounded-2xl shadow-soft border border-gray-100 p-5 transition hover:shadow-md hover:border-primary-200 flex flex-col sm:flex-row items-start sm:items-center gap-5 cursor-pointer relative">
                                
                                <div class="flex flex-col items-center justify-center bg-gray-50 group-hover:bg-primary-50 rounded-xl p-3 min-w-[80px] border border-gray-200 group-hover:border-primary-100 transition-colors">
                                    <span class="text-xs font-bold text-gray-400 group-hover:text-primary-400 uppercase">{{ \Carbon\Carbon::parse($agenda->scheduled_at)->format('M') }}</span>
                                    <span class="text-2xl font-extrabold text-gray-900 group-hover:text-primary-700">{{ \Carbon\Carbon::parse($agenda->scheduled_at)->format('d') }}</span>
                                    <span class="text-xs font-medium text-gray-500 group-hover:text-primary-600">{{ \Carbon\Carbon::parse($agenda->scheduled_at)->format('H:i') }}</span>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        @if(isset($agenda->type) && $agenda->type === 'exame')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-purple-50 text-purple-700 uppercase tracking-wide border border-purple-100">Exame</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-700 uppercase tracking-wide border border-blue-100">Consulta</span>
                                        @endif

                                        @if($agenda->status == 'confirmado')
                                            <span class="w-2 h-2 rounded-full bg-green-500" title="Confirmado"></span>
                                        @elseif($agenda->status == 'cancelado')
                                            <span class="w-2 h-2 rounded-full bg-red-500" title="Cancelado"></span>
                                        @else
                                            <span class="w-2 h-2 rounded-full bg-blue-500" title="Agendado"></span>
                                        @endif
                                    </div>
                                    <h4 class="font-bold text-gray-900 text-lg truncate">{{ $agenda->medico }}</h4>
                                    <p class="text-sm text-gray-500">{{ $agenda->specialty->name ?? $agenda->especialidade ?? 'Geral' }}</p>
                                </div>
                                
                                <div class="hidden sm:block pl-2">
                                    <div class="text-gray-300 group-hover:text-primary-600 transition-colors bg-white rounded-full p-2 group-hover:bg-primary-50">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">Resultados</h3>
                    <a href="{{ route('paciente.exames', ['tenant_slug' => $tenant->slug]) }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 hover:underline">Ver tudo</a>
                </div>

                @if($exames->isEmpty())
                    <div class="bg-white rounded-2xl shadow-sm p-8 text-center border border-gray-100">
                        <p class="text-gray-500 text-sm">Nenhum exame recente.</p>
                    </div>
                @else
                    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 divide-y divide-gray-50 overflow-hidden">
                        @foreach($exames as $exame)
                            @php
                                // --- MÁGICA PARA FUNCIONAR IGUAL A TELA DE EXAMES ---
                                
                                // 1. Converte para array para facilitar acesso (igual a tela de exames faz)
                                $ex = (array) $exame;

                                // 2. Pega os valores
                                $status = $ex['status'] ?? '';
                                $descricao = $ex['descricao'] ?? '';
                                $data = $ex['data'] ?? '';
                                
                                // 3. VERIFICAÇÃO DO LINK (Igual ao patient-exams.blade.php)
                                $linkLaudo = $ex['link_laudo'] ?? '#';
                                $urlPdf = $ex['url_pdf'] ?? null;
                                
                                // Tem arquivo? (Seja link externo ou PDF local)
                                $hasFile = ($linkLaudo != '#' && !empty($linkLaudo)) || !empty($urlPdf);
                                
                                // 4. Define o link final
                                $finalLink = $urlPdf ? asset('storage/'.$urlPdf) : $linkLaudo;

                                // 5. Lógica de Liberado (Se tem arquivo OU status textual bate)
                                $isLib = $hasFile || ($status == 'Liberado' || $status == 'LIB' || $status == 'LL');
                            @endphp

                            <div class="p-4 hover:bg-gray-50 transition group">
                                <div class="flex items-start gap-3">
                                    <div class="shrink-0 mt-1">
                                        @if($isLib)
                                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600" title="Disponível">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600" title="Em Análise">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-bold text-gray-800 text-sm truncate">{{ \Illuminate\Support\Str::limit($descricao, 25) }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-xs text-gray-400">{{ $data }}</span>
                                            
                                            @if($hasFile)
                                                <a href="{{ $finalLink }}" target="_blank" class="ml-auto text-[10px] font-bold uppercase bg-gray-100 text-gray-600 px-2 py-0.5 rounded hover:bg-primary-100 hover:text-primary-700 transition">
                                                    PDF
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>