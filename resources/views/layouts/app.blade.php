<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberAdmin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-slate-100 font-sans antialiased text-slate-800 h-screen overflow-hidden">

    <div x-data="{ sidebarExpanded: true }" class="flex h-full w-full">
        
        <aside :class="sidebarExpanded ? 'w-64' : 'w-20'" class="bg-slate-900 text-slate-300 flex flex-col justify-between border-r border-slate-800 shadow-2xl flex-shrink-0 transition-all duration-300 ease-in-out">
            <div>
                <div class="h-20 flex items-center justify-between px-4 border-b border-slate-800 bg-slate-950">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="p-2 bg-amber-500 rounded-lg text-slate-950 text-sm font-bold shadow-md flex-shrink-0">✂️</div>
                        <span x-show="sidebarExpanded" x-transition.opacity class="text-base font-bold tracking-wider text-white whitespace-nowrap">Barber<span class="text-amber-500">Shop</span></span>
                    </div>
                    <button @click="sidebarExpanded = !sidebarExpanded" class="p-1.5 rounded-lg hover:bg-slate-800 text-slate-400 hover:text-white hidden md:block">
                        <svg :class="sidebarExpanded ? 'rotate-0' : 'rotate-180'" class="h-5 w-5 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                    </button>
                </div>

                <nav class="px-3 py-6 space-y-1">
                    <p x-show="sidebarExpanded" class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 whitespace-nowrap">Operaciones</p>
                    
                    <a href="{{ route('dashboard') }}" title="Panel de Inicio" class="flex items-center gap-3 px-3 py-2.5 {{ Request::routeIs('dashboard') ? 'bg-amber-500 text-slate-950 font-semibold' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }} rounded-xl transition-all text-sm h-11">
                        <span class="text-lg flex-shrink-0">📊</span>
                        <span x-show="sidebarExpanded" x-transition.opacity class="whitespace-nowrap">Panel de Inicio</span>
                    </a>

                    <a href="{{ route('servicios.index') }}" title="Catálogo de Servicios" class="flex items-center gap-3 px-3 py-2.5 {{ Request::routeIs('servicios.*') ? 'bg-amber-500 text-slate-950 font-semibold' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }} rounded-xl transition-all text-sm h-11 group">
                        <span class="text-lg flex-shrink-0">✂️</span>
                        <span x-show="sidebarExpanded" x-transition.opacity class="whitespace-nowrap">Catálogo Servicios</span>
                    </a>

                    <a href="{{ route('ventas.index') }}" title="Nueva Venta / Corte" class="flex items-center gap-3 px-3 py-2.5 {{ Request::routeIs('ventas.*') ? 'bg-amber-500 text-slate-950 font-semibold' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }} rounded-xl transition-all text-sm h-11 group">
                        <span class="text-lg flex-shrink-0">💰</span>
                        <span x-show="sidebarExpanded" x-transition.opacity class="whitespace-nowrap">Nueva Venta</span>
                    </a>

                    <a href="{{ route('barberos.index') }}" title="Equipo Barberos" class="flex items-center gap-3 px-3 py-2.5 {{ Request::routeIs('barberos.index') ? 'bg-amber-500 text-slate-950 font-semibold' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }} rounded-xl transition-all text-sm h-11 group">
                        <span class="text-lg flex-shrink-0">💈</span>
                        <span x-show="sidebarExpanded" x-transition.opacity class="whitespace-nowrap">Equipo Barberos</span>
                    </a>

                    <p x-show="sidebarExpanded" class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest pt-6 mb-2 whitespace-nowrap">Administración</p>
                    
                    <a href="{{ route('cajachica.index') }}" title="Historial Cajas Chicas" class="flex items-center gap-3 px-3 py-2.5 {{ Request::routeIs('cajachica.*') ? 'bg-amber-500 text-slate-950 font-semibold' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }} rounded-xl transition-all text-sm h-11 group">
                        <span class="text-lg flex-shrink-0">💸</span>
                        <span x-show="sidebarExpanded" x-transition.opacity class="whitespace-nowrap">Historial Cajas Chica</span>
                    </a>

                    <a href="{{ route('reportes.index') }}" title="Reportes & Finanzas" class="flex items-center gap-3 px-3 py-2.5 {{ Request::routeIs('reportes.index') ? 'bg-amber-500 text-slate-950 font-semibold' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }} rounded-xl transition-all text-sm h-11 group">
                        <span class="text-lg flex-shrink-0">📈</span>
                        <span x-show="sidebarExpanded" x-transition.opacity class="whitespace-nowrap">Reportes & Finanzas</span>
                    </a>

                    <a href="{{ route('config.index') }}" title="Configuración" class="flex items-center gap-3 px-3 py-2.5 {{ Request::routeIs('config.index') ? 'bg-amber-500 text-slate-950 font-semibold' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }} rounded-xl transition-all text-sm h-11 group">
                        <span class="text-lg flex-shrink-0">⚙️</span>
                        <span x-show="sidebarExpanded" x-transition.opacity class="whitespace-nowrap">Configuración</span>
                    </a>
                </nav>
            </div>

            <div class="p-3 border-t border-slate-800 bg-slate-950/40">
                
                <div class="flex items-center gap-3 px-3 py-2 mb-2 overflow-hidden">
                    <div class="h-9 w-9 bg-amber-500 text-slate-950 rounded-xl flex items-center justify-center font-bold flex-shrink-0 text-sm shadow-md uppercase flex justify-center items-center">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div x-show="sidebarExpanded" x-transition.opacity class="flex flex-col min-w-0">
                        <span class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</span>
                        <span class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</span>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" title="Cerrar Sesión" class="w-full flex items-center gap-3 px-3 py-2.5 text-slate-400 hover:bg-red-500/10 hover:text-red-400 rounded-xl transition-all text-sm h-11 group">
                        <span class="text-lg flex-shrink-0 text-red-500 group-hover:scale-110 transition-transform">🚪</span>
                        <span x-show="sidebarExpanded" x-transition.opacity class="whitespace-nowrap font-medium">Cerrar Sesión</span>
                    </button>
                </form>

            </div>
        </aside>

        <main class="flex-1 h-full overflow-y-auto bg-slate-50 p-8 flex flex-col justify-between">
            <div class="flex-1">
                @yield('content')
            </div>
            <footer class="mt-8 pt-4 border-t border-slate-200 flex justify-between text-xs text-slate-400">
                <p>BarberAdmin v1.0</p>
                <p>Modo Escritorio</p>
            </footer>
        </main>
    </div>

</body>
</html>