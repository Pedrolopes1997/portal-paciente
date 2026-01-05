<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Primeiro Acesso - {{ $tenant->name ?? 'Portal do Paciente' }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: { 50: '#f0f9ff', 100: '#e0f2fe', 500: '#0ea5e9', 600: '#0284c7', 700: '#0369a1', 900: '#0c4a6e' },
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        /* Scrollbar fina para o modal */
        .modal-scroll::-webkit-scrollbar { width: 6px; }
        .modal-scroll::-webkit-scrollbar-track { background: #f1f1f1; }
        .modal-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    </style>
</head>

<body class="h-full font-sans antialiased text-gray-600" x-data="{ termsOpen: false, policyOpen: false }">

    <div class="flex min-h-full">
        
        <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24 bg-white z-10 w-full lg:w-[480px] xl:w-[600px]">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                
                <div class="flex justify-center lg:justify-start mb-10">
                    @if(isset($tenant->logo_path) && $tenant->logo_path)
                        <img class="h-12 w-auto object-contain" src="{{ asset('storage/'.$tenant->logo_path) }}" alt="{{ $tenant->name }}">
                    @else
                        <div class="flex items-center gap-2 text-primary-600">
                             <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                             <span class="font-bold text-2xl tracking-tight text-gray-900">{{ $tenant->name ?? 'Portal' }}</span>
                        </div>
                    @endif
                </div>

                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900">Primeiro Acesso</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Vamos localizar seu cadastro para criar seu usuário.
                    </p>
                </div>

                <div class="mt-8">
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Verifique os dados</h3>
                                    <div class="mt-1 text-sm text-red-700">
                                        <ul class="list-disc list-inside">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('paciente.primeiro-acesso.store', ['tenant_slug' => $tenant->slug]) }}" method="POST" class="space-y-5">
                        @csrf

                        <div x-data="{
                            cpf: '{{ old('cpf') }}',
                            formatar() {
                                // 1. Remove tudo que não for número
                                let value = this.cpf.replace(/\D/g, '');
                                
                                // 2. Limita a 11 números
                                if (value.length > 11) value = value.slice(0, 11);

                                // 3. Aplica a máscara (000.000.000-00)
                                if (value.length > 9) {
                                    value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{0,2})/, '$1.$2.$3-$4');
                                } else if (value.length > 6) {
                                    value = value.replace(/^(\d{3})(\d{3})(\d{0,3})/, '$1.$2.$3');
                                } else if (value.length > 3) {
                                    value = value.replace(/^(\d{3})(\d{0,3})/, '$1.$2');
                                }
                                
                                this.cpf = value;
                            }
                        }" x-init="formatar()"> <label class="block text-sm font-medium text-gray-900">CPF (Somente números)</label>
                            
                            <input 
                                type="text" 
                                name="cpf" 
                                x-model="cpf" 
                                @input="formatar()" 
                                required 
                                maxlength="14" 
                                placeholder="000.000.000-00"
                                class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 py-3 px-4 text-gray-900 focus:ring-2 focus:ring-primary-500 focus:bg-white transition shadow-sm placeholder-gray-400"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900">Data de Nascimento</label>
                            <input type="date" name="nascimento" required value="{{ old('nascimento') }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 py-3 px-4 text-gray-900 focus:ring-2 focus:ring-primary-500 focus:bg-white transition shadow-sm">
                        </div>

                        <div class="border-t border-gray-100 my-2"></div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900">Seu melhor E-mail</label>
                            <div class="relative mt-1">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                                </div>
                                <input type="email" name="email" required value="{{ old('email') }}" placeholder="email@exemplo.com"
                                    class="block w-full rounded-lg border-gray-300 bg-gray-50 py-3 pl-10 px-4 text-gray-900 focus:ring-2 focus:ring-primary-500 focus:bg-white transition shadow-sm">
                            </div>
                        </div>

                        <div x-data="{ show: false }">
                            <label class="block text-sm font-medium text-gray-900">Crie uma Senha</label>
                            <div class="relative mt-1">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                </div>
                                <input :type="show ? 'text' : 'password'" name="password" required placeholder="Mínimo 8 caracteres"
                                    class="block w-full rounded-lg border-gray-300 bg-gray-50 py-3 pl-10 pr-10 text-gray-900 focus:ring-2 focus:ring-primary-500 focus:bg-white transition shadow-sm">
                                
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900">Confirme a Senha</label>
                            <div class="relative mt-1">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                </div>
                                <input type="password" name="password_confirmation" required placeholder="Repita a senha"
                                    class="block w-full rounded-lg border-gray-300 bg-gray-50 py-3 pl-10 px-4 text-gray-900 focus:ring-2 focus:ring-primary-500 focus:bg-white transition shadow-sm">
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex h-6 items-center">
                                <input id="terms" name="terms" type="checkbox" required class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600 cursor-pointer">
                            </div>
                            <div class="ml-3 text-sm leading-6">
                                <label for="terms" class="font-medium text-gray-900 cursor-pointer">Li e concordo com os</label>
                                <button type="button" @click="termsOpen = true" class="font-semibold text-primary-600 hover:text-primary-500 hover:underline focus:outline-none">Termos de Uso</button> e 
                                <button type="button" @click="policyOpen = true" class="font-semibold text-primary-600 hover:text-primary-500 hover:underline focus:outline-none">Política de Privacidade</button>.
                            </div>
                        </div>
                        @error('terms') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror

                        <button type="submit" class="flex w-full justify-center rounded-lg bg-primary-600 px-3 py-3 text-sm font-bold leading-6 text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition-all transform hover:-translate-y-0.5 mt-6">
                            Verificar e Criar Conta
                        </button>
                    </form>

                    <div class="mt-8 text-center">
                        <a href="{{ route('paciente.login', ['tenant_slug' => $tenant->slug]) }}" class="font-medium text-primary-600 hover:text-primary-500 text-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Voltar para o Login
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative hidden w-0 flex-1 lg:block">
            <img class="absolute inset-0 h-full w-full object-cover" src="https://images.unsplash.com/photo-1631217868264-e5b90bb7e133?q=80&w=2091&auto=format&fit=crop" alt="Cadastro">
            <div class="absolute inset-0 bg-primary-900 mix-blend-multiply opacity-60"></div>
            <div class="absolute inset-0 flex flex-col justify-center px-12 text-white">
                <h2 class="text-4xl font-bold tracking-tight mb-4">Bem-vindo à família {{ $tenant->name }}.</h2>
                <p class="text-lg text-primary-100 max-w-xl">
                    Crie sua conta em segundos para ter acesso total à sua saúde, agendamentos e resultados de exames.
                </p>
            </div>
        </div>
        
    </div>

    <div x-show="termsOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="termsOpen" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="termsOpen = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="termsOpen" x-transition.scale class="relative inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">
                <div>
                    <div class="mt-3 text-center sm:mt-0 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Termos de Uso</h3>
                        <div class="mt-4 text-sm text-gray-500 max-h-96 overflow-y-auto modal-scroll pr-2 text-justify">
                            <p class="mb-3">
                                <strong>1. Aceitação dos Termos:</strong> Ao acessar e usar este Portal do Paciente, você concorda em cumprir estes termos de serviço, todas as leis e regulamentos aplicáveis.
                            </p>
                            <p class="mb-3">
                                <strong>2. Uso de Dados:</strong> Seus dados médicos são confidenciais. O portal exibe informações vindas diretamente do sistema hospitalar. Você é responsável por manter sua senha segura.
                            </p>
                            <p class="mb-3">
                                <strong>3. Isenção de Responsabilidade:</strong> As informações aqui contidas são para consulta. Em caso de dúvidas médicas, consulte seu médico presencialmente.
                            </p>
                            <p>
                                <strong>4. Alterações:</strong> Podemos revisar estes termos de serviço a qualquer momento sem aviso prévio. Ao usar este site, você concorda em ficar vinculado à versão atual desses termos de serviço.
                            </p>
                            </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6">
                    <button type="button" @click="termsOpen = false" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none sm:text-sm">
                        Entendi e Aceito
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div x-show="policyOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="policyOpen" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="policyOpen = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="policyOpen" x-transition.scale class="relative inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">
                <div>
                    <div class="mt-3 text-center sm:mt-0 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Política de Privacidade</h3>
                        <div class="mt-4 text-sm text-gray-500 max-h-96 overflow-y-auto modal-scroll pr-2 text-justify">
                            <p class="mb-3">
                                <strong>1. Coleta de Informações:</strong> Coletamos apenas as informações necessárias para identificar você no sistema hospitalar (CPF, Data de Nascimento) e para contato (E-mail).
                            </p>
                            <p class="mb-3">
                                <strong>2. Armazenamento:</strong> Registramos seu IP e data de aceite destes termos conforme exigido pela Lei Geral de Proteção de Dados (LGPD).
                            </p>
                            <p class="mb-3">
                                <strong>3. Cookies:</strong> Utilizamos cookies apenas para manter sua sessão de login ativa e segura. Não vendemos seus dados para terceiros.
                            </p>
                            <p>
                                <strong>4. Seus Direitos:</strong> Você tem o direito de solicitar a visualização ou exclusão dos seus dados de acesso ao portal a qualquer momento através do canal de suporte.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6">
                    <button type="button" @click="policyOpen = false" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none sm:text-sm">
                        Entendi
                    </button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>