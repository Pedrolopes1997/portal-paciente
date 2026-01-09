<div class="space-y-6">
    
    @if($exames->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm p-12 text-center border border-dashed border-gray-300 flex flex-col items-center justify-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-400">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Nenhum exame encontrado</h3>
            <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">
                Ainda não localizamos resultados liberados para o seu cadastro.
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4">
            @foreach($exames as $exame)
                @php
                    // --- PREPARAÇÃO DOS DADOS (Para limpar o HTML) ---
                    
                    // 1. Status
                    $statusRaw = $exame['status'] ?? '';
                    $statusUpper = strtoupper(trim($statusRaw));
                    $isLib = in_array($statusUpper, ['LIB', 'LIBERADO', 'CONCLUIDO', 'LL', 'DISPONÍVEL']);

                    // 2. Links (Prioriza PDF Local, se não tiver usa Link Externo/Tasy)
                    $linkLaudo = $exame['link_laudo'] ?? '#';
                    $urlPdf = $exame['url_pdf'] ?? null;
                    
                    // Tem arquivo disponível?
                    $hasFile = ($linkLaudo != '#' && !empty($linkLaudo)) || !empty($urlPdf);
                    
                    // Link final
                    $finalLink = (!empty($urlPdf)) ? asset('storage/'.$urlPdf) : $linkLaudo;
                @endphp

                <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-5 transition hover:shadow-md flex flex-col md:flex-row md:items-center gap-5 relative group overflow-hidden">
                    
                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $isLib ? 'bg-green-500' : 'bg-yellow-400' }}"></div>

                    <div class="shrink-0 flex items-center justify-center w-14 h-14 rounded-xl {{ $isLib ? 'bg-green-50 text-green-600' : 'bg-yellow-50 text-yellow-600' }}">
                        @if($isLib)
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @else
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ $exame['data'] }}</span>
                            
                            @if($isLib)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-800 uppercase tracking-wide">
                                    Disponível
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-yellow-100 text-yellow-800 uppercase tracking-wide">
                                    {{ ucfirst(strtolower($statusRaw)) }}
                                </span>
                            @endif
                        </div>
                        
                        <h4 class="text-lg font-bold text-gray-900 group-hover:text-primary-600 transition-colors">
                            {{ $exame['descricao'] }}
                        </h4>
                    </div>

                    <div class="mt-4 md:mt-0 md:ml-auto">
                        @if($hasFile)
                            <a href="{{ $finalLink }}" 
                               target="_blank"
                               class="flex items-center justify-center w-full md:w-auto px-5 py-2.5 bg-primary-600 text-white text-sm font-bold rounded-xl hover:bg-primary-700 shadow-md shadow-primary-500/20 transition-all transform hover:-translate-y-0.5 focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Baixar Resultado
                            </a>
                        @else
                            <div class="flex items-center text-gray-400 bg-gray-50 px-4 py-2 rounded-xl border border-gray-100 cursor-not-allowed select-none">
                                <svg class="w-5 h-5 mr-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="text-sm font-medium">Aguardando Laudo</span>
                            </div>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>
    @endif
</div>