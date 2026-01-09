<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Agenda do Dia - {{ date('d/m/Y') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print { .no-print { display: none; } }
    </style>
</head>
<body class="bg-gray-50 p-8 font-sans">
    
    <div class="max-w-4xl mx-auto bg-white p-8 shadow-lg border border-gray-200">
        
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 tracking-tight">{{ $tenant->name }}</h1>
                <p class="text-gray-500 text-sm uppercase tracking-wide">Relatório Diário de Agendamentos</p>
            </div>
            <div class="text-right">
                <p class="text-4xl font-bold text-gray-900">{{ date('d') }} <span class="text-lg text-gray-400 font-normal">/ {{ date('m') }}</span></p>
                <p class="text-xs text-gray-400 mt-1">{{ date('H:i') }}</p>
            </div>
        </div>

        <div class="no-print mb-6 text-center">
            <button onclick="window.print()" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-indigo-700 shadow-md transition transform hover:-translate-y-0.5">
                🖨️ Imprimir
            </button>
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200">
                    <th class="py-3 pl-2">Horário</th>
                    <th class="py-3">Tipo</th>
                    <th class="py-3">Paciente</th>
                    <th class="py-3">Profissional / Exame</th>
                    <th class="py-3 text-right pr-2">Situação</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @forelse($agendamentos as $agenda)
                
                {{-- TRADUÇÃO DE STATUS --}}
                @php
                    $statusLabel = match($agenda->status) {
                        'pending' => 'Pendente',
                        'confirmed' => 'Confirmado',
                        'agendado' => 'Agendado',
                        'realizado' => 'Realizado',
                        'cancelado' => 'Cancelado',
                        default => ucfirst($agenda->status)
                    };
                    
                    $statusColor = match($agenda->status) {
                        'confirmed', 'realizado' => 'text-green-600 bg-green-50 border-green-200',
                        'cancelado' => 'text-red-600 bg-red-50 border-red-200',
                        'pending' => 'text-yellow-600 bg-yellow-50 border-yellow-200',
                        default => 'text-gray-600 bg-gray-100 border-gray-200'
                    };
                @endphp

                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <td class="py-4 pl-2 font-mono font-bold text-base text-gray-900">{{ $agenda->scheduled_at->format('H:i') }}</td>
                    
                    <td class="py-4 font-medium">
                        {{-- CORREÇÃO: Lógica separada para Exame e Consulta --}}
                        @if($agenda->type === 'exame')
                            {{-- Para exames, pegamos direto do atributo 'medico' do banco (getRawOriginal logic via attributes) --}}
                            {{ $agenda->getAttributes()['medico'] ?? $agenda->specialty->name ?? 'Exame' }}
                        @else
                            {{-- Para consultas, priorizamos o relacionamento doctor --}}
                            {{ $agenda->doctor->name ?? $agenda->getAttributes()['medico'] ?? '-' }}
                        @endif
                    </td>
                    
                    <td class="py-4">
                        <div class="font-bold text-gray-900">{{ $agenda->patient->name }}</div>
                        <div class="text-xs text-gray-400 flex items-center gap-1 mt-0.5">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            {{ $agenda->patient->celular ?? 'Sem contato' }}
                        </div>
                    </td>
                    
                    <td class="py-4 font-medium">
                        {{ $agenda->medico }} 
                        {{-- Aqui ele exibe o Nome do Médico OU o Nome do Exame, conforme salvamos no painel --}}
                    </td>
                    
                    <td class="py-4 text-right pr-2">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold border {{ $statusColor }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 text-center text-gray-400 italic bg-gray-50 rounded-lg border border-dashed border-gray-200">
                        Nenhum agendamento encontrado para hoje.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>