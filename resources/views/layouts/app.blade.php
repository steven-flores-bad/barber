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
        
        <!-- SIDEBAR COLAPSABLE -->
        <aside :class="sidebarExpanded ? 'w-64' : 'w-20'" class="bg-slate-900 text-slate-300 flex flex-col justify-between border-r border-slate-800 shadow-2xl flex-shrink-0 transition-all duration-300 ease-in-out">
            <div>
                <!-- CABECERA -->
                <div class="h-20 flex items-center justify-between px-4 border-b border-slate-800 bg-slate-950">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="p-2 bg-amber-500 rounded-lg text-slate-950 text-sm font-bold shadow-md flex-shrink-0">✂️</div>
                        <span x-show="sidebarExpanded" x-transition.opacity class="text-base font-bold tracking-wider text-white whitespace-nowrap">Barber<span class="text-amber-500">Admin</span></span>
                    </div>
                    <button @click="sidebarExpanded = !sidebarExpanded" class="p-1.5 rounded-lg hover:bg-slate-800 text-slate-400 hover:text-white hidden md:block">
                        <svg :class="sidebarExpanded ? 'rotate-0' : 'rotate-180'" class="h-5 w-5 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                    </button>
                </div>

                <!-- NAVEGACIÓN CON LAS RUTAS DINÁMICAS -->
                <nav class="px-3 py-6 space-y-1">
                    <p x-show="sidebarExpanded" class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 whitespace-nowrap">Operaciones</p>
                    
                    <!-- Enlace Inicio -->
                    <a href="{{ route('dashboard') }}" title="Panel de Inicio" class="flex items-center gap-3 px-3 py-2.5 {{ Request::routeIs('dashboard') ? 'bg-amber-500 text-slate-950 font-semibold' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }} rounded-xl transition-all text-sm h-11">
                        <span class="text-lg flex-shrink-0">📊</span>
                        <span x-show="sidebarExpanded" x-transition.opacity class="whitespace-nowrap">Panel de Inicio</span>
                    </a>

                    <!-- Enlace Nueva Venta -->
                    <a href="{{ route('ventas.create') }}" title="Nueva Venta / Corte" class="flex items-center gap-3 px-3 py-2.5 {{ Request::routeIs('ventas.create') ? 'bg-amber-500 text-slate-950 font-semibold' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }} rounded-xl transition-all text-sm h-11 group">
                        <span class="text-lg flex-shrink-0">💰</span>
                        <span x-show="sidebarExpanded" x-transition.opacity class="whitespace-nowrap">Nueva Venta</span>
                    </a>

                    <!-- Enlace Barberos -->
                    <a href="{{ route('barberos.index') }}" title="Equipo Barberos" class="flex items-center gap-3 px-3 py-2.5 {{ Request::routeIs('barberos.index') ? 'bg-amber-500 text-slate-950 font-semibold' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }} rounded-xl transition-all text-sm h-11 group">
                        <span class="text-lg flex-shrink-0">💈</span>
                        <span x-show="sidebarExpanded" x-transition.opacity class="whitespace-nowrap">Equipo Barberos</span>
                    </a>

                    <p x-show="sidebarExpanded" class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest pt-6 mb-2 whitespace-nowrap">Administración</p>

                    <!-- Enlace Reportes -->
                    <a href="{{ route('reportes.index') }}" title="Reportes & Finanzas" class="flex items-center gap-3 px-3 py-2.5 {{ Request::routeIs('reportes.index') ? 'bg-amber-500 text-slate-950 font-semibold' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }} rounded-xl transition-all text-sm h-11 group">
                        <span class="text-lg flex-shrink-0">📈</span>
                        <span x-show="sidebarExpanded" x-transition.opacity class="whitespace-nowrap">Reportes & Finanzas</span>
                    </a>

                    <!-- Enlace Configuración -->
                    <a href="{{ route('config.index') }}" title="Configuración" class="flex items-center gap-3 px-3 py-2.5 {{ Request::routeIs('config.index') ? 'bg-amber-500 text-slate-950 font-semibold' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }} rounded-xl transition-all text-sm h-11 group">
                        <span class="text-lg flex-shrink-0">⚙️</span>
                        <span x-show="sidebarExpanded" x-transition.opacity class="whitespace-nowrap">Configuración</span>
                    </a>

                </nav>
            </div>
        </aside>

        <!-- ÁREA DE TRABAJO -->
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