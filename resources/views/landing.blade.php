<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WeCare - Gestão Inteligente para Clínicas e Hospitais</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        /* Efeito sutil de gradiente no fundo */
        .bg-hero-pattern {
            background-image: radial-gradient(circle at top right, #f0f9ff, #fff 40%);
        }
    </style>
</head>
<body class="bg-white text-slate-800 antialiased">

    <nav class="w-full py-6 px-6 lg:px-12 flex justify-between items-center max-w-7xl mx-auto absolute top-0 left-0 right-0 z-50">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-sky-600 font-bold text-2xl hover:opacity-80 transition duration-300">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            WeCare
        </a>
        <div class="hidden md:flex gap-8 font-medium text-slate-600">
            <a href="#funcionalidades" class="hover:text-sky-600 transition py-2">Funcionalidades</a>
            <a href="#beneficios" class="hover:text-sky-600 transition py-2">Benefícios</a>
            <a href="#integracao" class="hover:text-sky-600 transition py-2">Integração</a>
        </div>
        <div class="flex gap-4 items-center">
            <a href="/painel" class="text-slate-600 font-medium hover:text-sky-600 py-2 hidden sm:block">Área do Cliente</a>
            <a href="#contato" class="bg-sky-600 hover:bg-sky-700 text-white px-6 py-3 rounded-full font-bold transition shadow-lg shadow-sky-200 hover:shadow-sky-300 hover:-translate-y-1">
                Contratar
            </a>
        </div>
    </nav>

    <section class="bg-hero-pattern pt-32 pb-20 lg:pt-40 lg:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 grid lg:grid-cols-5 gap-16 items-center">
            <div class="lg:col-span-2">
                <div class="inline-flex items-center gap-2 bg-sky-50 text-sky-700 px-4 py-2 rounded-full text-sm font-bold mb-6 border border-sky-100 animate-fade-in-up">
                    <span class="flex h-2 w-2 relative justify-center items-center">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-sky-500"></span>
                    </span>
                    A revolução no atendimento ao paciente
                </div>
                <h1 class="text-5xl lg:text-6xl font-bold leading-tight mb-6 text-slate-900 tracking-tight">
                    Seu paciente conectado, sua clínica <span class="text-sky-600 bg-sky-50 px-2 rounded-lg relative inline-block">organizada.</span>
                </h1>
                <p class="text-lg text-slate-600 mb-8 leading-relaxed">
                    Elimine filas e papelada. Ofereça um portal moderno onde seus pacientes agendam consultas e acessam resultados de exames 24/7. Compatível com Tasy e MV.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="#contato" class="bg-sky-600 hover:bg-sky-700 text-white px-8 py-4 rounded-2xl font-bold text-center transition shadow-xl shadow-sky-200 hover:-translate-y-1">
                        Quero Modernizar Minha Clínica
                    </a>
                    <a href="/painel" class="bg-white hover:bg-slate-50 text-slate-700 border-2 border-slate-200 px-8 py-4 rounded-2xl font-bold text-center transition hover:border-slate-300 hover:-translate-y-1 flex justify-center items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-sky-600">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                        Acessar Demonstração
                    </a>
                </div>
                <p class="mt-8 text-sm text-slate-500 flex flex-wrap items-center gap-x-6 gap-y-2 font-medium">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Setup Rápido
                    </span>
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Segurança LGPD
                    </span>
                </p>
            </div>
            
            <div class="lg:col-span-3 relative z-10">
                <div class="rounded-2xl bg-slate-900/5 p-2 backdrop-blur-sm ring-1 ring-inset ring-slate-900/10 lg:-m-4 lg:rounded-3xl lg:p-4">
                    <div class="rounded-xl overflow-hidden shadow-2xl ring-1 ring-slate-900/10 bg-white">
                        <div class="bg-slate-100 border-b border-slate-200 flex items-center gap-2 px-4 py-3">
                            <div class="flex gap-1.5">
                                <div class="w-3 h-3 rounded-full bg-slate-300"></div>
                                <div class="w-3 h-3 rounded-full bg-slate-300"></div>
                                <div class="w-3 h-3 rounded-full bg-slate-300"></div>
                            </div>
                            <div class="text-xs font-medium text-slate-400 flex-1 text-center mx-4 bg-white py-1 rounded-md border border-slate-200">portal.suaclinica.com.br</div>
                        </div>
                        <img src="{{ asset('images/portal-screenshot.png') }}" alt="Interface do Portal do Paciente WeCare" class="w-full h-auto">
                    </div>
                </div>
                <div class="absolute -top-24 -right-24 -z-10 w-[130%] h-[130%] bg-gradient-to-tr from-sky-100 via-sky-50 to-white rounded-full blur-3xl opacity-50"></div>
            </div>
        </div>
    </section>

    <section id="funcionalidades" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-base text-sky-600 font-bold tracking-wide uppercase mb-3">Funcionalidades Essenciais</h2>
                <p class="text-4xl font-bold text-slate-900 mb-6">Tudo para uma jornada do paciente sem atritos</p>
                <p class="text-xl text-slate-600 leading-relaxed">Uma suíte completa que moderniza sua recepção e encanta seus pacientes desde o primeiro clique.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-10">
                <div class="bg-slate-50 p-10 rounded-3xl border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition duration-300 group">
                    <div class="bg-white w-16 h-16 rounded-2xl flex items-center justify-center text-sky-600 mb-8 shadow-sm group-hover:scale-110 transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-slate-900">Agendamento Online 24h</h3>
                    <p class="text-slate-600 leading-relaxed">Permita que seu paciente marque consultas a qualquer hora, de qualquer lugar. Reduza o volume de ligações na recepção.</p>
                </div>

                <div class="bg-slate-50 p-10 rounded-3xl border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition duration-300 group">
                    <div class="bg-white w-16 h-16 rounded-2xl flex items-center justify-center text-indigo-600 mb-8 shadow-sm group-hover:scale-110 transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-slate-900">Entrega de Resultados</h3>
                    <p class="text-slate-600 leading-relaxed">Disponibilize laudos e imagens de exames em PDF automaticamente. Economize com impressão e logística.</p>
                </div>

                <div class="bg-slate-50 p-10 rounded-3xl border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition duration-300 group">
                    <div class="bg-white w-16 h-16 rounded-2xl flex items-center justify-center text-teal-600 mb-8 shadow-sm group-hover:scale-110 transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-slate-900">Integração ERP Nativa</h3>
                    <p class="text-slate-600 leading-relaxed">Conectamos diretamente ao banco Oracle (Tasy) ou outros ERPs. Seus dados sincronizados em tempo real, sem gambiarras.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="beneficios" class="py-24 bg-slate-900 text-white overflow-hidden relative">
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 opacity-20">
             <svg class="absolute right-0 top-0 text-slate-800 w-1/2 h-auto" viewBox="0 0 100 100" fill="currentColor"><path d="M0 0 L100 0 L100 100 C50 100 50 0 0 0 Z"></path></svg>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-12 relative z-10 grid lg:grid-cols-2 gap-16 items-center">
            <div>
                <h2 class="text-base text-sky-400 font-bold tracking-wide uppercase mb-3">Por que WeCare?</h2>
                <p class="text-4xl font-bold mb-8 leading-tight">Mais do que software. <br>Tranquilidade para sua gestão.</p>
                <p class="text-xl text-slate-300 mb-12 leading-relaxed">
                    Focamos na segurança e na estabilidade que uma operação de saúde exige, permitindo que você foque no cuidado ao paciente.
                </p>
                
                <div class="space-y-8">
                    <div class="flex gap-6">
                        <div class="w-14 h-14 rounded-xl bg-sky-600/20 flex items-center justify-center text-sky-400 shrink-0">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold mb-2">Segurança de Nível Bancário</h4>
                            <p class="text-slate-400">Criptografia de ponta a ponta e conformidade total com a LGPD. Os dados médicos dos seus pacientes estão protegidos.</p>
                        </div>
                    </div>
                    <div class="flex gap-6">
                        <div class="w-14 h-14 rounded-xl bg-teal-600/20 flex items-center justify-center text-teal-400 shrink-0">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold mb-2">Redução de No-Shows (Faltas)</h4>
                            <p class="text-slate-400">Pacientes engajados com o portal tendem a faltar menos. Aumente a ocupação da sua agenda e seu faturamento.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative lg:h-[600px] rounded-3xl overflow-hidden shadow-2xl border border-slate-800/50">
                <img src="{{ asset('images/medica-segurando-tablet.png') }}" alt="Médica utilizando tablet" class="w-full h-full object-cover opacity-90 hover:scale-105 transition duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-10">
                    <blockquote class="text-2xl font-medium italic text-white mb-6">
                        "Desde que implementamos a WeCare, as ligações na recepção caíram 40% e os pacientes adoram a facilidade de pegar os exames online."
                    </blockquote>
                    <div>
                        <p class="font-bold text-lg">Dra. Juliana Paes</p>
                        <p class="text-slate-400">Diretora Clínica, Hospital Santa Vida</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contato" class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-sky-600"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
        
        <div class="max-w-4xl mx-auto px-6 lg:px-12 text-center relative z-10 text-white">
            <h2 class="text-4xl lg:text-5xl font-bold mb-8 leading-tight">Pronto para modernizar o atendimento da sua clínica?</h2>
            <p class="text-xl text-sky-100 mb-12 leading-relaxed max-w-2xl mx-auto">
                Junte-se a clínicas e hospitais que já transformaram a experiência dos seus pacientes. Solicite uma demonstração personalizada hoje.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <button class="bg-white text-sky-600 hover:bg-sky-50 px-10 py-5 rounded-2xl font-bold text-lg transition shadow-xl hover:-translate-y-1">
                    Falar com um Consultor
                </button>
                <a href="/painel" class="bg-sky-700 text-white border-2 border-sky-500 hover:bg-sky-800 hover:border-sky-400 px-10 py-5 rounded-2xl font-bold text-lg transition hover:-translate-y-1">
                    Ver o Sistema Funcionando
                </a>
            </div>
            <p class="mt-8 text-sky-200 text-sm">Resposta em até 24 horas úteis.</p>
        </div>
    </section>

    <footer class="bg-slate-950 text-slate-400 py-16">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            <div class="col-span-1 md:col-span-2">
                <a href="#" class="flex items-center gap-2 text-white font-bold text-2xl mb-6">
                    <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    WeCare
                </a>
                <p class="text-slate-500 leading-relaxed mb-6 max-w-md">Transformando a saúde através da tecnologia. Conectamos pacientes e clínicas de forma simples, segura e eficiente.</p>
            </div>
            <div>
                <h6 class="text-white font-bold mb-6 uppercase tracking-wider text-sm">Produto</h6>
                <ul class="space-y-4">
                    <li><a href="#funcionalidades" class="hover:text-white transition">Funcionalidades</a></li>
                    <li><a href="#beneficios" class="hover:text-white transition">Benefícios</a></li>
                    <li><a href="#integracao" class="hover:text-white transition">Integração Tasy/MV</a></li>
                    <li><a href="/painel" class="text-sky-500 font-medium hover:text-sky-400 transition">Área do Paciente</a></li>
                </ul>
            </div>
            <div>
                <h6 class="text-white font-bold mb-6 uppercase tracking-wider text-sm">Empresa</h6>
                <ul class="space-y-4">
                    <li><a href="#" class="hover:text-white transition">Sobre Nós</a></li>
                    <li><a href="#" class="hover:text-white transition">Contato</a></li>
                    <li><a href="/admin" class="hover:text-white transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Acesso Administrativo
                    </a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-6 lg:px-12 pt-8 border-t border-slate-800 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-slate-500">
            <div>&copy; {{ date('Y') }} WeCare Tecnologia em Saúde. Todos os direitos reservados.</div>
            <div class="flex gap-6">
                <a href="#" class="hover:text-white transition">Termos de Uso</a>
                <a href="#" class="hover:text-white transition">Política de Privacidade</a>
            </div>
        </div>
    </footer>

</body>
</html>