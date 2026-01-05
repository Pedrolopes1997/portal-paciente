<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso Negado</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full text-center">
        <div class="mb-4">
            <svg class="h-16 w-16 text-red-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Acesso Restrito</h1>
        
        @auth
            <p class="text-gray-600 mb-6">
                Você está logado como <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }}), 
                mas esse usuário não tem permissão para acessar este painel.
            </p>

            <a href="{{ route('logout.emergencia') }}" class="block w-full text-center bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-150 shadow-md">
                Sair e Trocar de Conta
            </a>
        @else
            <p class="text-gray-600 mb-6">Sua sessão expirou ou você não tem acesso.</p>
            <a href="/admin/login" class="inline-block w-full bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">
                Fazer Login
            </a>
        @endauth
    </div>
</body>
</html>