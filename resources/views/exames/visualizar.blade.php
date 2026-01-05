@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-6">
    <div class="bg-white p-6 rounded-t-xl border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">{{ $exame->ds_exame }}</h2>
            <p class="text-sm text-gray-500">
                Liberado em: {{ \Carbon\Carbon::parse($exame->dt_liberacao)->format('d/m/Y H:i') }}
            </p>
        </div>
        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
            &larr; Voltar
        </a>
    </div>

    <div class="bg-white p-8 rounded-b-xl shadow-sm min-h-[400px]">
        <div class="prose max-w-none text-gray-800 font-serif leading-relaxed">
            {!! nl2br($exame->ds_laudo) !!}
        </div>

        <div class="mt-12 pt-6 border-t border-gray-100 text-center">
            <p class="text-xs text-gray-400 uppercase tracking-wider">Documento assinado digitalmente</p>
            <p class="text-xs text-gray-300 mt-1">ID: {{ $exame->nr_sequencia }}</p>
        </div>
    </div>
</div>
@endsection