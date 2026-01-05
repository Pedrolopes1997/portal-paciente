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
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Confirmado</span>
                            @elseif($agenda->status == 'cancelado')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Cancelado</span>
                            @elseif($agenda->status == 'realizado')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Realizado</span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Agendado</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if(($agenda->status == 'agendado' || $agenda->status == 'confirmado'))
                                
                                @if($tenant->erp_driver === 'tasy' && $tenant->whatsapp)
                                    @php
                                        $msg = "Olá, gostaria de solicitar o cancelamento do meu agendamento do dia " . \Carbon\Carbon::parse($agenda->data_agendamento)->format('d/m') . " com " . $agenda->medico;
                                        $link = "https://wa.me/" . $tenant->whatsapp . "?text=" . urlencode($msg);
                                    @endphp
                                    <a href="{{ $link }}" target="_blank" class="text-red-600 hover:text-red-900 font-bold text-xs uppercase tracking-wide border border-red-200 px-3 py-1 rounded hover:bg-red-50 transition">
                                        Cancelar
                                    </a>
                                
                                @elseif($tenant->erp_driver !== 'tasy')
                                    <form action="{{ route('paciente.agendamentos.cancelar', ['tenant_slug' => $tenant->slug, 'id' => $agenda->id]) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja cancelar esta consulta?');">
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