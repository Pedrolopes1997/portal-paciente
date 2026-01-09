<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tenant->name ?? 'Portal do Paciente' }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: {
                            50: '#eff6ff', 100: '#dbeafe', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 900: '#1e3a8a',
                        },
                    },
                    boxShadow: {
                        'soft': '0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02)',
                    }
                }
            }
        }
    </script>
    
    @livewireStyles
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style> 
        [x-cloak] { display: none !important; } 
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="h-full bg-gray-50 text-gray-600 antialiased overflow-hidden">
    
    <div x-data="{ sidebarOpen: false }" class="flex h-full">

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-gray-900/50 backdrop-blur-sm transition-opacity md:hidden" x-transition.opacity x-cloak></div>

        <aside 
            class="fixed inset-y-0 left-0 z-30 w-72 bg-white border-r border-gray-100 shadow-soft transform transition-transform duration-300 ease-in-out md:relative md:translate-x-0 flex flex-col"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="h-20 flex items-center px-8 border-b border-gray-50">
                <a href="{{ route('paciente.dashboard', ['tenant_slug' => request()->route('tenant_slug')]) }}" class="flex items-center gap-2 transition-opacity hover:opacity-80">
                    @if(isset($tenant->logo_path) && $tenant->logo_path)
                        <img src="{{ asset('storage/' . $tenant->logo_path) }}" alt="{{ $tenant->name }}" class="h-8 w-auto object-contain">
                    @else
                        <div class="bg-primary-100 p-2 rounded-lg text-primary-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        </div>
                        <span class="font-bold text-lg text-gray-800 tracking-tight">{{ $tenant->name ?? 'WeCare' }}</span>
                    @endif
                </a>
            </div>

            <div class="p-6 pb-2">
                <a href="{{ route('paciente.agendar', ['tenant_slug' => request()->route('tenant_slug')]) }}" 
                   class="flex items-center justify-center w-full gap-2 px-4 py-3 text-sm font-bold text-white transition-all transform bg-primary-600 rounded-xl shadow-lg shadow-primary-500/30 hover:bg-primary-700 hover:-translate-y-0.5 hover:shadow-primary-500/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Novo Agendamento
                </a>
            </div>

            <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-2">Navegação</p>
                
                <a href="{{ route('paciente.dashboard', ['tenant_slug' => request()->route('tenant_slug')]) }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('paciente.dashboard') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('paciente.dashboard') ? 'text-primary-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Início
                </a>

                <a href="{{ route('paciente.agenda', ['tenant_slug' => request()->route('tenant_slug')]) }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('paciente.agenda') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                   <svg class="w-5 h-5 mr-3 {{ request()->routeIs('paciente.agenda') ? 'text-primary-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Minha Agenda
                </a>

                <a href="{{ route('paciente.exames', ['tenant_slug' => request()->route('tenant_slug')]) }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('paciente.exames*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                   <svg class="w-5 h-5 mr-3 {{ request()->routeIs('paciente.exames*') ? 'text-primary-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Resultados
                </a>
            </nav>

            <div class="p-4 border-t border-gray-50">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                    <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-primary-600 font-bold shadow-sm">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-900 truncate">{{ explode(' ', auth()->user()->name)[0] }}</p>
                        <a href="{{ route('paciente.profile', ['tenant_slug' => request()->route('tenant_slug')]) }}" class="text-xs text-primary-600 hover:underline">Meu Perfil</a>
                    </div>
                    <form method="POST" action="{{ route('paciente.logout', ['tenant_slug' => request()->route('tenant_slug')]) }}">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-red-500 p-1.5 rounded-lg hover:bg-white transition-colors" title="Sair">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-full overflow-hidden relative">
            
            <header class="md:hidden bg-white border-b border-gray-200 h-16 flex items-center px-4 justify-between flex-shrink-0 z-10">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-primary-600 p-2 rounded-lg hover:bg-gray-50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div class="font-bold text-gray-700">{{ $tenant->name ?? 'Portal' }}</div>
                <div class="w-10"></div>
            </header>

            <main class="flex-1 overflow-y-auto bg-gray-50 scroll-smooth p-4 md:p-8">
                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>
        </div>
    </div>
    @livewireScripts
</body>
</html>