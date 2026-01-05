<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Página não encontrada - WeCare</title>
</head>
<body class="bg-gray-100 h-screen flex flex-col items-center justify-center">
    <div class="text-center">
        <h1 class="text-9xl font-bold text-teal-600">404</h1>
        <h2 class="text-2xl font-semibold text-gray-800 mt-4">Ops! Página não encontrada.</h2>
        <p class="text-gray-500 mt-2">O link que você tentou acessar não existe ou foi movido.</p>
        
        <a href="{{ url('/') }}" class="mt-6 inline-block px-6 py-3 bg-teal-600 text-white font-bold rounded-lg hover:bg-teal-700 transition">
            Voltar ao Início
        </a>
    </div>
    
    <div class="mt-12 text-gray-400 text-sm">
        &copy; {{ date('Y') }} WeCare Health System
    </div>
</body>
</html>