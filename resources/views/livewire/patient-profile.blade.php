<div class="min-h-screen bg-gray-50/50 pb-12">

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             class="fixed top-4 right-4 z-50 flex items-center w-full max-w-xs p-4 space-x-3 text-gray-500 bg-white rounded-xl shadow-lg border border-gray-100 transform transition-all duration-300" role="alert">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
            </div>
            <div class="ml-3 text-sm font-medium">{{ session('success') }}</div>
        </div>
    @endif

    <div class="relative mb-8">
        <div class="h-40 w-full bg-gradient-to-r from-blue-700 via-indigo-600 to-purple-700 lg:h-56 relative overflow-hidden">
            <svg class="absolute inset-0 h-full w-full opacity-10" xmlns="http://www.w3.org/2000/svg">
                <defs><pattern id="dots" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1" fill="white"/></pattern></defs>
                <rect width="100%" height="100%" fill="url(#dots)"/>
            </svg>
        </div>
        
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative -mt-12 sm:-mt-16">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col sm:flex-row items-center sm:items-end gap-6 backdrop-blur-sm bg-white/95">
                
                <div class="h-24 w-24 sm:h-28 sm:w-28 rounded-full ring-4 ring-white shadow-md bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center -mt-10 sm:-mt-16 flex-shrink-0">
                    <span class="text-3xl font-bold text-gray-400 uppercase">
                        {{ substr($dadosCadastrais->nome ?? $user->name, 0, 2) }}
                    </span>
                </div>
                
                <div class="flex-1 text-center sm:text-left">
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                        {{ $dadosCadastrais->nome ?? $user->name }}
                    </h1>
                    <div class="mt-1 flex flex-wrap items-center justify-center sm:justify-start gap-3 text-sm text-gray-500">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 font-medium border border-blue-100">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Paciente
                        </span>
                        <span class="hidden sm:inline text-gray-300">|</span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            {{ $dadosCadastrais->email_tasy ?? $user->email }}
                        </span>
                    </div>
                </div>

                @if($tenant->whatsapp)
                <div class="sm:self-center">
                    <a href="https://wa.me/{{ $tenant->whatsapp }}?text=Preciso%20de%20ajuda%20com%20meu%20cadastro" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-200 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        Ajuda
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <h3 class="text-base font-semibold text-gray-900">Documentação</h3>
                        <span class="text-xs font-medium bg-gray-100 text-gray-500 px-2 py-1 rounded border border-gray-200">Apenas Leitura</span>
                    </div>
                    
                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="group">
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1.5">CPF</label>
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100 group-hover:border-indigo-100 transition-colors">
                                <div class="p-2 bg-white rounded-md text-gray-400 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                </div>
                                <span class="font-mono text-gray-700 font-medium">{{ $dadosCadastrais->cpf ?? '---' }}</span>
                            </div>
                        </div>

                        <div class="group">
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1.5">Cartão SUS</label>
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100 group-hover:border-indigo-100 transition-colors">
                                <div class="p-2 bg-white rounded-md text-gray-400 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </div>
                                <span class="font-mono text-gray-700 font-medium">{{ $dadosCadastrais->cns ?? '---' }}</span>
                            </div>
                        </div>

                        <div class="sm:col-span-2 group">
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1.5">Nome da Mãe</label>
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100 group-hover:border-indigo-100 transition-colors">
                                <div class="p-2 bg-white rounded-md text-gray-400 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <span class="text-gray-700 font-medium">{{ $dadosCadastrais->nome_mae ?? '---' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-base font-semibold text-gray-900">Endereço</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600 mt-1 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                @if(isset($dadosCadastrais->endereco))
                    <p class="text-gray-900 font-medium">
                        {{ $dadosCadastrais->endereco }}, {{ $dadosCadastrais->numero ?? 'S/N' }}
                        
                        @if(!empty($dadosCadastrais->complemento))
                             - {{ $dadosCadastrais->complemento }}
                        @endif
                    </p>
                    <p class="text-gray-500 text-sm mt-0.5">{{ $dadosCadastrais->bairro ?? '' }}</p>
                    <p class="text-gray-500 text-sm">{{ $dadosCadastrais->cidade ?? '' }} / {{ $dadosCadastrais->uf ?? '' }}</p>
                    <p class="text-xs text-gray-400 mt-1">CEP: {{ $dadosCadastrais->cep ?? '' }}</p>
                @else
                    <p class="text-gray-400 italic">Endereço não cadastrado.</p>
                @endif
            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1">
                <form wire:submit.prevent="updateProfile" class="bg-white rounded-xl shadow-sm border border-gray-100 sticky top-6 overflow-hidden">
                    
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Segurança da Conta
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">Dados de login no portal.</p>
                    </div>

                    <div class="p-6 space-y-5">
                        
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">E-mail</label>
                            <input type="email" wire:model="email" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                            @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="relative py-2">
                            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-100"></div></div>
                            <div class="relative flex justify-center"><span class="bg-white px-2 text-xs text-gray-400 uppercase tracking-widest font-medium">Alterar Senha</span></div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Senha Atual</label>
                            <input type="password" wire:model="current_password" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors" placeholder="••••••••">
                            @error('current_password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nova Senha</label>
                            <input type="password" wire:model="password" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors" placeholder="••••••••">
                            @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Confirmar Senha</label>
                            <input type="password" wire:model="password_confirmation" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        <button type="submit" wire:loading.attr="disabled" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all disabled:opacity-70 disabled:cursor-not-allowed">
                            <span wire:loading.remove>Salvar Alterações</span>
                            <span wire:loading class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Processando...
                            </span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>