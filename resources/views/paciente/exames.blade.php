@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Histórico de Exames</h1>
        <p class="text-gray-500">Consulte e baixe seus laudos.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($exames->isEmpty())
             <div class="p-8 text-center text-gray-500">Nenhum exame encontrado.</div>
        @else
             <table class="min-w-full divide-y divide-gray-200">
                 <thead class="bg-gray-50">
                     <tr>
                         <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exame</th>
                         <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                         <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                         <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ação</th>
                     </tr>
                 </thead>
                 <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($exames as $exame)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $exame['descricao'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $exame['data'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($exame['status'] == 'Liberado')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Liberado</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ $exame['status'] }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if(isset($exame['link_laudo']) && $exame['link_laudo'] != '#')
                                    <a href="{{ $exame['link_laudo'] }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">Baixar PDF</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                 </tbody>
             </table>
        @endif
    </div>
@endsection