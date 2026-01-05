@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 pb-12">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Minha Agenda</h1>
                <p class="text-gray-500 mt-1">Consulte seus agendamentos futuros e histórico recente.</p>
            </div>
            
            @if($tenant->whatsapp)
                <a href="https://wa.me/{{ $tenant->whatsapp }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 shadow-sm transition">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    Novo Agendamento
                </a>
            @endif
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            @if($agendamentos->isEmpty())
                <div class="p-12 text-center">
                    <div class="text-gray-300 mb-4">
                         <svg class="mx-auto h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">Nenhum agendamento encontrado</h3>
                    <p class="mt-1 text-sm text-gray-500">Você não possui consultas agendadas no momento.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Data / Hora</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Profissional</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Especialidade</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="relative px-6 py-4">
                                    <span class="sr-only">Ações</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($agendamentos as $agenda)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-indigo-50 rounded-lg text-indigo-600 font-bold text-xs border border-indigo-100">
                                            {{ \Carbon\Carbon::parse($agenda->data_agendamento)->format('d') }}
                                            <br>
                                            {{ \Carbon\Carbon::parse($agenda->data_agendamento)->format('M') }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($agenda->data_agendamento)->format('d/m/Y') }}</div>
                                            <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($agenda->data_agendamento)->format('H:i') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $agenda->medico }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $agenda->especialidade ?? 'Consulta' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($agenda->status == 'confirmado')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Confirmado
                                        </span>
                                    @elseif($agenda->status == 'cancelado')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Cancelado
                                        </span>
                                    @elseif($agenda->status == 'realizado')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Realizado
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Agendado
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    {{-- Lógica de Cancelamento Híbrida (Tasy vs Local) --}}
                                    @if($agenda->status == 'agendado' || $agenda->status == 'confirmado')
                                        
                                        @if($tenant->erp_driver === 'tasy' && $tenant->whatsapp)
                                            {{-- Cancelar via WhatsApp (Tasy) --}}
                                            @php
                                                $msg = "Olá, gostaria de solicitar o cancelamento do meu agendamento do dia " . \Carbon\Carbon::parse($agenda->data_agendamento)->format('d/m') . " com " . $agenda->medico;
                                                $link = "https://wa.me/" . $tenant->whatsapp . "?text=" . urlencode($msg);
                                            @endphp
                                            <a href="{{ $link }}" target="_blank" class="text-red-600 hover:text-red-900 font-bold text-xs uppercase tracking-wide border border-red-200 px-3 py-1 rounded hover:bg-red-50 transition">
                                                Cancelar
                                            </a>
                                        
                                        @elseif($tenant->erp_driver !== 'tasy')
                                            {{-- Cancelar via Banco (Local) --}}
                                            <form action="{{ route('paciente.agendamentos.cancelar', ['id' => $agenda->id]) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja cancelar esta consulta?');">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold text-xs uppercase tracking-wide border border-red-200 px-3 py-1 rounded hover:bg-red-50 transition">
                                                    Cancelar
                                                </button>
                                            </form>
                                        @endif
                                    
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection