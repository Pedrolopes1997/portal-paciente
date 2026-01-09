<div class="pb-12">

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             x-transition:enter="transform ease-out duration-300 transition"
             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed top-24 right-4 z-50 max-w-sm w-full bg-white shadow-lg rounded-2xl pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-bold text-gray-900">Sucesso!</p>
                        <p class="mt-1 text-sm text-gray-500">{{ session('success') }}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="show = false" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Meu Perfil</h1>
            <p class="mt-2 text-gray-500">Gerencie suas informações de acesso e visualize seus dados cadastrais.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-6 flex flex-col sm:flex-row items-center gap-6">
                    <div class="h-24 w-24 rounded-full bg-gradient-to-br from-primary-100 to-primary-200 text-primary-600 flex items-center justify-center text-3xl font-bold shadow-inner shrink-0">
                        {{ substr($dadosCadastrais->nome ?? $user->name, 0, 2) }}
                    </div>
                    <div class="text-center sm:text-left flex-1">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $dadosCadastrais->nome ?? $user->name }}</h2>
                        <p class="text-gray-500 flex items-center justify-center sm:justify-start gap-2 mt-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            {{ $dadosCadastrais->email_tasy ?? $user->email }}
                        </p>
                        <div class="mt-3 flex flex-wrap justify-center sm:justify-start gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-blue-50 text-blue-700 uppercase tracking-wide border border-blue-100">
                                Paciente
                            </span>
                            @if(!empty($dadosCadastrais->cns))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-gray-50 text-gray-600 uppercase tracking-wide border border-gray-100">
                                    CNS: {{ $dadosCadastrais->cns }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    @if($tenant->whatsapp)
                        <div class="sm:self-start">
                            <a href="https://wa.me/{{ $tenant->whatsapp }}?text=Preciso%20atualizar%20meus%20dados%20cadastrais" target="_blank" class="text-xs font-bold text-primary-600 hover:text-primary-800 hover:underline flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Solicitar alteração de dados
                            </a>
                        </div>
                    @endif
                </div>

                <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                        <h3 class="text-base font-bold text-gray-900">Ficha Cadastral</h3>
                        <span class="text-[10px] font-bold uppercase bg-gray-200 text-gray-500 px-2 py-1 rounded">Somente Leitura</span>
                    </div>
                    
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase">CPF</p>
                                <p class="text-gray-900 font-medium font-mono">{{ $dadosCadastrais->cpf ?? '---' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-pink-50 rounded-lg text-pink-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase">Nome da Mãe</p>
                                <p class="text-gray-900 font-medium">{{ $dadosCadastrais->nome_mae ?? '---' }}</p>
                            </div>
                        </div>

                        <div class="md:col-span-2 flex items-start gap-3 pt-4 border-t border-gray-50">
                            <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase">Endereço Residencial</p>
                                @if(isset($dadosCadastrais->endereco))
                                    <p class="text-gray-900 font-medium">
                                        {{ $dadosCadastrais->endereco }}, {{ $dadosCadastrais->numero ?? 'S/N' }}
                                        @if(!empty($dadosCadastrais->complemento)) - {{ $dadosCadastrais->complemento }} @endif
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $dadosCadastrais->bairro ?? '' }} - {{ $dadosCadastrais->cidade ?? '' }}/{{ $dadosCadastrais->uf ?? '' }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1 font-mono">CEP: {{ $dadosCadastrais->cep ?? '' }}</p>
                                @else
                                    <p class="text-gray-400 italic text-sm">Endereço não informado.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <form wire:submit.prevent="updateProfile" class="bg-white rounded-2xl shadow-soft border border-gray-100 sticky top-6 overflow-hidden">
                    
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Acesso e Segurança
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">Atualize seu e-mail e senha de acesso.</p>
                    </div>

                    <div class="p-6 space-y-5">
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">E-mail de Login</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                                </div>
                                <input type="email" wire:model="email" class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-xl bg-gray-50 focus:bg-white transition-colors py-2.5" placeholder="seu@email.com">
                            </div>
                            @error('email') <span class="text-xs text-red-500 mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div class="relative py-2">
                            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-100"></div></div>
                            <div class="relative flex justify-center"><span class="bg-white px-2 text-xs text-gray-400 uppercase tracking-widest font-bold">Alterar Senha</span></div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Senha Atual</label>
                            <input type="password" wire:model="current_password" class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-xl bg-gray-50 focus:bg-white transition-colors py-2.5" placeholder="••••••••">
                            @error('current_password') <span class="text-xs text-red-500 mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nova Senha</label>
                            <input type="password" wire:model="password" class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-xl bg-gray-50 focus:bg-white transition-colors py-2.5" placeholder="Mínimo 8 caracteres">
                            @error('password') <span class="text-xs text-red-500 mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Confirmar Senha</label>
                            <input type="password" wire:model="password_confirmation" class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-xl bg-gray-50 focus:bg-white transition-colors py-2.5" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        <button type="submit" wire:loading.attr="disabled" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg shadow-primary-500/30 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all transform hover:-translate-y-0.5 disabled:opacity-70 disabled:cursor-not-allowed">
                            <span wire:loading.remove>Salvar Alterações</span>
                            <span wire:loading class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Salvando...
                            </span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>