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
                            50: '#f0f9ff', 100: '#e0f2fe', 500: '#0ea5e9', 600: '#0284c7', 700: '#0369a1', 900: '#0c4a6e',
                        },
                    }
                }
            }
        }
    </script>
    
    @livewireStyles
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style> 
        [x-cloak] { display: none !important; } 
        /* Scrollbar personalizada para o menu e conteúdo */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
    </style>
</head>

<body class="h-full font-sans text-gray-600 antialiased bg-gray-100 overflow-hidden">
    
    <div x-data="{ sidebarOpen: window.innerWidth >= 768 }" class="flex h-full">

        <aside 
            class="flex-shrink-0 bg-white border-r border-gray-200 transition-all duration-300 ease-in-out flex flex-col fixed inset-y-0 left-0 z-30 md:relative"
            :class="sidebarOpen ? 'w-64 translate-x-0' : 'w-0 -translate-x-full md:w-0 md:translate-x-0'"
        >
            <div class="h-16 flex items-center px-4 border-b border-gray-100 flex-shrink-0 bg-white justify-between overflow-hidden whitespace-nowrap">
                
                <div class="flex items-center gap-3 w-full">
                    @if(isset($tenant->logo_path) && $tenant->logo_path)
                        <img src="{{ asset('storage/' . $tenant->logo_path) }}" 
                            alt="{{ $tenant->name }}" 
                            class="h-10 w-auto object-contain max-w-full">
                    @else
                        <div class="flex items-center gap-2 text-primary-700">
                            <svg class="w-8 h-8 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            <span class="font-bold text-lg tracking-tight leading-none">
                                {{ $tenant->name ?? 'Portal' }}
                            </span>
                        </div>
                    @endif
                </div>

                <button @click="sidebarOpen = false" class="md:hidden text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto custom-scrollbar overflow-x-hidden">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Menu</p>
                
                <a href="{{ route('paciente.dashboard', ['tenant_slug' => request()->route('tenant_slug')]) }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg group transition-all whitespace-nowrap {{ request()->routeIs('paciente.dashboard') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('paciente.dashboard') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>Início</span>
                </a>

                <a href="{{ route('paciente.agenda', ['tenant_slug' => request()->route('tenant_slug')]) }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg group transition-all whitespace-nowrap {{ request()->routeIs('paciente.agenda*') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                   <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('paciente.agenda*') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>Minha Agenda</span>
                </a>

                <a href="{{ route('paciente.exames', ['tenant_slug' => request()->route('tenant_slug')]) }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg group transition-all whitespace-nowrap {{ request()->routeIs('paciente.exames*') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                   <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('paciente.exames*') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Resultados</span>
                </a>
            </nav>

            <div class="p-4 border-t border-gray-200 bg-gray-50 flex-shrink-0">
                <div class="flex items-center justify-between w-full group">
                    
                    <a href="{{ route('paciente.profile', ['tenant_slug' => request()->route('tenant_slug')]) }}" class="flex items-center gap-3 min-w-0 flex-1 hover:opacity-80 transition-opacity" title="Editar Perfil">
                        <div class="w-9 h-9 rounded-full bg-white border border-gray-200 flex items-center justify-center text-primary-600 font-bold shadow-sm flex-shrink-0">
                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                        </div>
                        <div class="overflow-hidden min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-xs text-gray-500 truncate">
                                Ver perfil
                            </p>
                        </div>
                    </a>

                    <form method="POST" action="{{ route('paciente.logout', ['tenant_slug' => request()->route('tenant_slug')]) }}">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-red-500 p-2 rounded-md hover:bg-red-50 transition-colors" title="Sair">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false" 
             class="fixed inset-0 bg-gray-900/50 z-20 md:hidden"
             x-transition.opacity
             x-cloak>
        </div>

        <div class="flex-1 flex flex-col h-full overflow-hidden relative">
            
            <header class="bg-white border-b border-gray-200 h-16 flex items-center px-4 justify-between sm:px-6 lg:px-8 flex-shrink-0">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-primary-600 focus:outline-none p-2 rounded hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                <div class="md:hidden font-bold text-gray-700 truncate max-w-[200px]">
                    {{ $tenant->name }}
                </div>
                
                <div class="w-6 hidden md:block"></div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50/50 scroll-smooth">
                @yield('content')
            </main>
        </div>

    </div>

    @livewireScripts
</body>
</html>