<div class="space-y-6">

    {{-- 1. MENU DE ABAS (Controlado pelo Livewire) --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-1 flex items-center justify-between md:justify-start gap-2">
        <button 
            wire:click="setTab('proximos')"
            class="flex-1 md:flex-none px-6 py-2.5 rounded-lg text-sm transition-all duration-200 flex items-center justify-center gap-2 focus:outline-none 
            {{ $activeTab === 'proximos' ? 'bg-primary-50 text-primary-700 font-bold shadow-sm ring-1 ring-primary-200' : 'text-gray-500 hover:bg-gray-50' }}">
            
            {{-- Spinner de Carregamento ao Clicar --}}
            <svg wire:loading wire:target="setTab('proximos')" class="animate-spin -ml-1 mr-2 h-4 w-4 text-primary-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            
            Próximos
            <span class="bg-white px-2 py-0.5 rounded-full text-xs border border-gray-100 shadow-sm text-gray-800">
                {{ $proximos->count() }}
            </span>
        </button>

        <button 
            wire:click="setTab('historico')"
            class="flex-1 md:flex-none px-6 py-2.5 rounded-lg text-sm transition-all duration-200 flex items-center justify-center gap-2 focus:outline-none 
            {{ $activeTab === 'historico' ? 'bg-gray-100 text-gray-800 font-bold shadow-sm ring-1 ring-gray-200' : 'text-gray-500 hover:bg-gray-50' }}">
            
            {{-- Spinner de Carregamento ao Clicar --}}
            <svg wire:loading wire:target="setTab('historico')" class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>

            Histórico
            <span class="bg-white px-2 py-0.5 rounded-full text-xs border border-gray-100 shadow-sm text-gray-800">
                {{ $historico->count() }}
            </span>
        </button>
    </div>

    {{-- 2. CONTEÚDO: PRÓXIMOS --}}
    @if($activeTab === 'proximos')
        <div class="space-y-10 animate-fade-in-up">
            @php
                $listaConsultas = $proximos->filter(fn($a) => ($a->type ?? 'consulta') !== 'exame');
                $listaExames = $proximos->filter(fn($a) => ($a->type ?? '') === 'exame');
            @endphp

            @if($proximos->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm p-12 text-center border border-dashed border-gray-300 flex flex-col items-center justify-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-400">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Nenhum agendamento futuro</h3>
                    <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">Você não possui consultas ou exames agendados para os próximos dias.</p>
                    <div class="mt-6">
                        <a href="{{ route('paciente.agendar', ['tenant_slug' => $tenant->slug]) }}" class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-bold rounded-xl text-white bg-green-600 hover:bg-green-700 transition-transform transform hover:-translate-y-0.5">Novo Agendamento</a>
                    </div>
                </div>
            @else
                @if($listaConsultas->isNotEmpty())
                    <section>
                        <div class="flex items-center gap-3 mb-5">
                            <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Próximas Consultas</h2>
                        </div>
                        <div class="space-y-4">
                            @foreach($listaConsultas as $agenda)
                                <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-5 transition hover:shadow-md flex flex-col md:flex-row gap-5 relative overflow-hidden group">
                                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $agenda->status == 'confirmado' ? 'bg-green-500' : ($agenda->status == 'cancelado' ? 'bg-red-400' : 'bg-blue-500') }}"></div>
                                    <div class="flex flex-col items-center justify-center bg-gray-50 rounded-xl p-3 min-w-[80px] border border-gray-200">
                                        <span class="text-xs font-bold text-gray-400 uppercase">{{ \Carbon\Carbon::parse($agenda->scheduled_at)->format('M') }}</span>
                                        <span class="text-2xl font-extrabold text-gray-900">{{ \Carbon\Carbon::parse($agenda->scheduled_at)->format('d') }}</span>
                                        <span class="text-xs font-medium text-gray-500">{{ \Carbon\Carbon::parse($agenda->scheduled_at)->format('H:i') }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-700 uppercase tracking-wide border border-blue-100">Consulta</span>
                                            @if($agenda->status == 'confirmado') <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-md uppercase">Confirmado</span>
                                            @elseif($agenda->status == 'cancelado') <span class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-md uppercase">Cancelado</span>
                                            @else <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md uppercase">Agendado</span> @endif
                                        </div>
                                        <h4 class="font-bold text-gray-900 text-lg">{{ $agenda->medico }}</h4>
                                        <p class="text-sm text-gray-500 font-medium">{{ $agenda->specialty->name ?? $agenda->especialidade ?? 'Geral' }}</p>
                                    </div>
                                    <div class="md:text-right mt-4 md:mt-0 flex flex-wrap items-center justify-end gap-2">
                                        @if($agenda->status == 'agendado')
                                            @if($tenant->whatsapp)
                                                <a href="https://wa.me/{{ $tenant->whatsapp }}?text={{ urlencode('Confirmar consulta ' . \Carbon\Carbon::parse($agenda->scheduled_at)->format('d/m')) }}" target="_blank" class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-bold rounded-lg text-white bg-green-600 hover:bg-green-700 transition">Confirmar</a>
                                            @endif
                                            <form action="{{ route('paciente.agendamentos.cancelar', ['tenant_slug' => $tenant->slug, 'id' => $agenda->id]) }}" method="POST" onsubmit="return confirm('Confirmar cancelamento?');">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-3 py-2 border border-red-100 text-xs font-bold rounded-lg text-red-600 bg-white hover:bg-red-50 transition">Cancelar</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if($listaExames->isNotEmpty())
                    <section>
                        <div class="flex items-center gap-3 mb-5 mt-8">
                            <div class="p-2 bg-purple-100 text-purple-600 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Próximos Exames</h2>
                        </div>
                        <div class="space-y-4">
                            @foreach($listaExames as $agenda)
                                <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-5 transition hover:shadow-md flex flex-col md:flex-row gap-5 relative overflow-hidden group">
                                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $agenda->status == 'confirmado' ? 'bg-green-500' : ($agenda->status == 'cancelado' ? 'bg-red-400' : 'bg-purple-500') }}"></div>
                                    <div class="flex flex-col items-center justify-center bg-gray-50 rounded-xl p-3 min-w-[80px] border border-gray-200">
                                        <span class="text-xs font-bold text-gray-400 uppercase">{{ \Carbon\Carbon::parse($agenda->scheduled_at)->format('M') }}</span>
                                        <span class="text-2xl font-extrabold text-gray-900">{{ \Carbon\Carbon::parse($agenda->scheduled_at)->format('d') }}</span>
                                        <span class="text-xs font-medium text-gray-500">{{ \Carbon\Carbon::parse($agenda->scheduled_at)->format('H:i') }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-purple-50 text-purple-700 uppercase tracking-wide border border-purple-100">Exame</span>
                                            @if($agenda->status == 'confirmado') <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-md uppercase">Confirmado</span>
                                            @elseif($agenda->status == 'cancelado') <span class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-md uppercase">Cancelado</span>
                                            @else <span class="text-[10px] font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded-md uppercase">Agendado</span> @endif
                                        </div>
                                        <h4 class="font-bold text-gray-900 text-lg">{{ $agenda->medico }}</h4>
                                        <p class="text-sm text-gray-500 font-medium">{{ $agenda->specialty->name ?? $agenda->especialidade ?? 'Imagem/Laboratório' }}</p>
                                    </div>
                                    <div class="md:text-right mt-4 md:mt-0 flex flex-wrap items-center justify-end gap-2">
                                        @if($agenda->status == 'agendado')
                                            @if($tenant->whatsapp)
                                                <a href="https://wa.me/{{ $tenant->whatsapp }}?text={{ urlencode('Confirmar exame ' . \Carbon\Carbon::parse($agenda->scheduled_at)->format('d/m')) }}" target="_blank" class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-bold rounded-lg text-white bg-green-600 hover:bg-green-700 transition">Confirmar</a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endif
        </div>
    @endif

    {{-- 3. CONTEÚDO: HISTÓRICO --}}
    @if($activeTab === 'historico')
        <div class="space-y-10 animate-fade-in-up">
            @php
                $histConsultas = $historico->filter(fn($a) => strtolower($a->type ?? 'consulta') !== 'exame');
                $histExames = $historico->filter(fn($a) => strtolower($a->type ?? '') === 'exame');
            @endphp

            @if($historico->isEmpty())
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4 text-gray-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Histórico Vazio</h3>
                    <p class="mt-1 text-sm text-gray-500">Nenhum agendamento passado foi encontrado.</p>
                </div>
            @else
                @if($histConsultas->isNotEmpty())
                    <section>
                        <div class="flex items-center gap-3 mb-5">
                            <div class="p-2 bg-gray-100 text-gray-600 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Consultas Realizadas</h2>
                        </div>
                        <div class="space-y-4 opacity-75 grayscale-[0.3]">
                            @foreach($histConsultas as $agenda)
                                <div class="bg-gray-50 rounded-2xl shadow-sm border border-gray-200 p-5 flex flex-col md:flex-row gap-5 relative overflow-hidden">
                                    <div class="flex flex-col items-center justify-center bg-white rounded-xl p-3 min-w-[80px] border border-gray-200">
                                        <span class="text-xs font-bold text-gray-400 uppercase">{{ \Carbon\Carbon::parse($agenda->scheduled_at)->format('M') }}</span>
                                        <span class="text-2xl font-extrabold text-gray-700">{{ \Carbon\Carbon::parse($agenda->scheduled_at)->format('d') }}</span>
                                        <span class="text-xs font-medium text-gray-500">{{ \Carbon\Carbon::parse($agenda->scheduled_at)->format('H:i') }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-gray-200 text-gray-700 uppercase tracking-wide">Passado</span>
                                            <span class="text-[10px] font-bold text-gray-600 bg-gray-100 px-2 py-0.5 rounded-md uppercase">{{ $agenda->status }}</span>
                                        </div>
                                        <h4 class="font-bold text-gray-900 text-lg">{{ $agenda->medico }}</h4>
                                        <p class="text-sm text-gray-500 font-medium">{{ $agenda->specialty->name ?? $agenda->especialidade }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if($histExames->isNotEmpty())
                    <section>
                        <div class="flex items-center gap-3 mb-5 mt-8">
                            <div class="p-2 bg-gray-100 text-gray-600 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Exames Realizados</h2>
                        </div>
                        <div class="space-y-4 opacity-75 grayscale-[0.3]">
                            @foreach($histExames as $agenda)
                                <div class="bg-gray-50 rounded-2xl shadow-sm border border-gray-200 p-5 flex flex-col md:flex-row gap-5 relative overflow-hidden">
                                    <div class="flex flex-col items-center justify-center bg-white rounded-xl p-3 min-w-[80px] border border-gray-200">
                                        <span class="text-xs font-bold text-gray-400 uppercase">{{ \Carbon\Carbon::parse($agenda->scheduled_at)->format('M') }}</span>
                                        <span class="text-2xl font-extrabold text-gray-700">{{ \Carbon\Carbon::parse($agenda->scheduled_at)->format('d') }}</span>
                                        <span class="text-xs font-medium text-gray-500">{{ \Carbon\Carbon::parse($agenda->scheduled_at)->format('H:i') }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-gray-200 text-gray-700 uppercase tracking-wide">Exame</span>
                                            <span class="text-[10px] font-bold text-gray-600 bg-gray-100 px-2 py-0.5 rounded-md uppercase">{{ $agenda->status }}</span>
                                        </div>
                                        <h4 class="font-bold text-gray-900 text-lg">{{ $agenda->medico }}</h4>
                                        <p class="text-sm text-gray-500 font-medium">{{ $agenda->specialty->name ?? $agenda->especialidade }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endif
        </div>
    @endif
</div>