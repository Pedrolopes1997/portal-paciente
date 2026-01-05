@extends('layouts.app')

@section('content')
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
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border-l-4 border-indigo-500 p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Carteirinha</p>
                    <p class="text-lg font-bold text-gray-800">{{ $paciente['carteirinha'] }}</p>
                </div>
                <div class="p-3 bg-indigo-50 rounded-full text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border-l-4 border-teal-500 p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Plano</p>
                    <p class="text-lg font-bold text-gray-800">{{ $paciente['plano'] }}</p>
                </div>
                <div class="p-3 bg-teal-50 rounded-full text-teal-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border-l-4 border-purple-500 p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Exames</p>
                    <p class="text-lg font-bold text-gray-800">{{ count($exames) }} Disponíveis</p>
                </div>
                <div class="p-3 bg-purple-50 rounded-full text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <section>
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Meus Agendamentos
                </h3>

                @if($agendamentos->isEmpty())
                    <div class="bg-white rounded-xl shadow-sm p-8 text-center border border-gray-100">
                        <div class="text-gray-300 mb-3">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-gray-500 font-medium">Você não tem agendamentos futuros.</p>
                        <p class="text-xs text-gray-400 mt-1">Entre em contato para marcar uma consulta.</p>
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
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Confirmado
                                            </span>
                                        @elseif($agenda->status == 'cancelado')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Cancelado
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Agendado
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between items-center">
                                    
                                    @if($tenant->whatsapp)
                                        @php
                                            $msg = "Olá, sou *" . $user->name . "*. Tenho dúvida sobre agendamento dia *" . \Carbon\Carbon::parse($agenda->data_agendamento)->format('d/m') . "* com *" . $agenda->medico . "*.";
                                            $linkZap = "https://wa.me/" . $tenant->whatsapp . "?text=" . urlencode($msg);
                                        @endphp
                                        <a href="{{ $linkZap }}" target="_blank" class="flex items-center text-xs font-medium text-gray-500 hover:text-green-600 transition">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                            Ajuda
                                        </a>
                                    @endif

                                    @if($agenda->status == 'agendado' || $agenda->status == 'confirmado')
                                        @if($tenant->erp_driver === 'tasy' && $tenant->whatsapp)
                                            @php
                                                $msgCancel = "Olá, sou *" . $user->name . "*. Quero CANCELAR meu agendamento dia *" . \Carbon\Carbon::parse($agenda->data_agendamento)->format('d/m') . "*.";
                                                $linkCancel = "https://wa.me/" . $tenant->whatsapp . "?text=" . urlencode($msgCancel);
                                            @endphp
                                            <a href="{{ $linkCancel }}" target="_blank" class="text-xs text-red-500 hover:text-red-700 font-bold uppercase tracking-wide">Cancelar</a>
                                        @elseif($tenant->erp_driver !== 'tasy')
                                            <form action="{{ route('paciente.agendamentos.cancelar', ['id' => $agenda->id]) }}" method="POST" onsubmit="return confirm('Confirmar cancelamento?')">
                                                @csrf
                                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-bold uppercase tracking-wide">Cancelar</button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <section>
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Exames Recentes
                </h3>

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
                                        @if($exame['status'] == 'Liberado' || $exame['status'] == 'LIBERADO')
                                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-800 text-sm truncate pr-2">{{ \Illuminate\Support\Str::limit($exame['descricao'], 35) }}</p>
                                        <p class="text-xs text-gray-500">Realizado em {{ $exame['data'] }}</p>
                                    </div>
                                </div>

                                <div>
                                    @if(isset($exame['link_laudo']) && $exame['link_laudo'] != '#')
                                        <a href="{{ $exame['link_laudo'] }}" target="_blank" class="inline-flex items-center px-3 py-1.5 border border-indigo-600 text-indigo-600 rounded-lg text-xs font-bold hover:bg-indigo-600 hover:text-white transition shadow-sm">
                                            Ver Laudo
                                        </a>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-gray-100 text-gray-500">
                                            Em análise
                                        </span>
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
@endsection