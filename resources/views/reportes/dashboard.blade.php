@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Encabezado -->
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">📊 Centro de Reportes</h1>
        <p class="text-slate-500 text-base mt-1.5">Selecciona el tipo de informe financiero u operativo que deseas analizar.</p>
    </div>

    <!-- Grid Principal de 2 Columnas (con 2 tarjetas cada una en pantallas medianas/grandes) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
        
        <!-- Tarjeta 1: Ventas Detalladas -->
        <a href="{{ route('reportes.ventas') }}" class="group bg-white p-8 border border-slate-200 rounded-3xl shadow-sm hover:border-amber-500 hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 hover:scale-[1.02] flex flex-col justify-between cursor-pointer min-h-[220px]">
            <div class="space-y-4">
                <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl font-bold group-hover:bg-amber-500 group-hover:text-white transition-all duration-300 shadow-sm">
                    ✂️
                </div>
                <h3 class="font-extrabold text-slate-900 text-xl group-hover:text-amber-600 transition-colors">
                    Ventas Detalladas
                </h3>
                <p class="text-slate-500 text-base leading-relaxed">
                    Listado individual de cada corte atendido en la barbería con su precio y hora.
                </p>
            </div>
            <div class="mt-6 flex items-center">
                <span class="text-sm font-bold text-slate-400 group-hover:text-slate-900 group-hover:translate-x-1 transition-all">
                    Ver Detalles &rarr;
                </span>
            </div>
        </a>

        <!-- Tarjeta 2: Ganancias del Día -->
        <a href="{{ route('reportes.cierre') }}" class="group bg-white p-8 border border-slate-200 rounded-3xl shadow-sm hover:border-emerald-500 hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 hover:scale-[1.02] flex flex-col justify-between cursor-pointer min-h-[220px]">
            <div class="space-y-4">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl font-bold group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300 shadow-sm">
                    💵
                </div>
                <h3 class="font-extrabold text-slate-900 text-xl group-hover:text-emerald-600 transition-colors">
                    Ganancias del Día
                </h3>
                <p class="text-slate-500 text-base leading-relaxed">
                    Cierre de caja rápido y automatizado del día actual para auditoría de ingresos.
                </p>
            </div>
            <div class="mt-6 flex items-center">
                <span class="text-sm font-bold text-slate-400 group-hover:text-slate-900 group-hover:translate-x-1 transition-all">
                    Calcular Caja &rarr;
                </span>
            </div>
        </a>

        <!-- Tarjeta 3: Rendimiento Barbero -->
        <a href="{{ route('reportes.barberos') }}" class="group bg-white p-8 border border-slate-200 rounded-3xl shadow-sm hover:border-blue-500 hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 hover:scale-[1.02] flex flex-col justify-between cursor-pointer min-h-[220px]">
            <div class="space-y-4">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl font-bold group-hover:bg-blue-500 group-hover:text-white transition-all duration-300 shadow-sm">
                    💈
                </div>
                <h3 class="font-extrabold text-slate-900 text-xl group-hover:text-blue-600 transition-colors">
                    Rendimiento Barbero
                </h3>
                <p class="text-slate-500 text-base leading-relaxed">
                    Filtros acumulados por Día, Semana y Mes para medir cortes y productividad.
                </p>
            </div>
            <div class="mt-6 flex items-center">
                <span class="text-sm font-bold text-slate-400 group-hover:text-slate-900 group-hover:translate-x-1 transition-all">
                    Medir Personal &rarr;
                </span>
            </div>
        </a>

        <!-- Tarjeta 4: Top Servicios -->
        <a href="{{ route('reportes.top') }}" class="group bg-white p-8 border border-slate-200 rounded-3xl shadow-sm hover:border-purple-500 hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 hover:scale-[1.02] flex flex-col justify-between cursor-pointer min-h-[220px]">
            <div class="space-y-4">
                <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl font-bold group-hover:bg-purple-500 group-hover:text-white transition-all duration-300 shadow-sm">
                    🔥
                </div>
                <h3 class="font-extrabold text-slate-900 text-xl group-hover:text-purple-600 transition-colors">
                    Top Servicios
                </h3>
                <p class="text-slate-500 text-base leading-relaxed">
                    Ranking estadístico de los cortes y servicios más solicitados por los clientes.
                </p>
            </div>
            <div class="mt-6 flex items-center">
                <span class="text-sm font-bold text-slate-400 group-hover:text-slate-900 group-hover:translate-x-1 transition-all">
                    Ver Top 10 &rarr;
                </span>
            </div>
        </a>

    </div>
</div>
@endsection