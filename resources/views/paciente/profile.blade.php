@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Meu Perfil</h1>
        <p class="mt-1 text-sm text-gray-500">Gerencie suas informações de acesso e segurança.</p>
    </div>

    @if (session('success'))
        <div class="rounded-md bg-green-50 p-4 mb-6 border-l-4 border-green-500 shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('paciente.profile.update', ['tenant_slug' => $tenant->slug]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6">
            
            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Informações da Conta</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Dados utilizados para acessar o portal.</p>
                </div>
                <div class="px-4 py-5 sm:p-6 space-y-6">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nome Completo</label>
                        <div class="mt-1">
                            <input type="text" value="{{ $user->name }}" disabled
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md bg-gray-100 text-gray-500 cursor-not-allowed">
                            <p class="mt-1 text-xs text-gray-400">O nome é gerenciado pelo hospital e não pode ser alterado aqui.</p>
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Endereço de E-mail</label>
                        <div class="mt-1">
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                </div>
            </div>

            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Alterar Senha</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Deixe em branco se não quiser alterar sua senha atual.</p>
                </div>
                <div class="px-4 py-5 sm:p-6 space-y-6">
                    
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Senha Atual</label>
                        <div class="mt-1">
                            <input type="password" name="current_password" id="current_password" autocomplete="current-password"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                        @error('current_password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Nova Senha</label>
                            <div class="mt-1">
                                <input type="password" name="password" id="password" autocomplete="new-password"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Nova Senha</label>
                            <div class="mt-1">
                                <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                    </div>

                </div>
                
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 border-t border-gray-200">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                        Salvar Alterações
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection