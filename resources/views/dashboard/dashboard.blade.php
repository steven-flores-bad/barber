@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    {{-- 1. ENCABEZADO PREMIUM DE BIENVENIDA --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-slate-900 text-white p-6 rounded-2xl shadow-lg relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 text-9xl text-slate-800 opacity-20 pointer-events-none">💈</div>
        <div class="relative z-10">
            <h1 class="text-2xl md:text-3xl font-black tracking-tight">¡Bienvenido al Panel de Control!</h1>
            <p class="text-slate-400 text-sm mt-1">Aquí tienes el resumen y las métricas comerciales de la barbería para el día de hoy.</p>
        </div>
        <div class="flex flex-wrap gap-3 relative z-10 w-full md:w-auto">
            <a href="{{ route('ventas.index') }}" class="flex-1 md:flex-none text-center px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl transition-all text-sm shadow-md flex items-center justify-center gap-2">
                <span>💰</span> Ver Ventas
            </a>
            <a href="{{ route('barberos.index') }}" class="flex-1 md:flex-none text-center px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-medium rounded-xl transition-all text-sm border border-slate-700 flex items-center justify-center gap-2">
                <span>👨‍🎨</span> Ver Equipo
            </a>
        </div>
    </div>

    {{-- 2. INDICADORES CLAVE DE RENDIMIENTO (KPIs) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        {{-- Tarjeta 1: Caja Chica / Recaudación --}}
        <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Caja del Día</p>
                <p class="text-3xl font-black text-slate-900 mt-1">${{ number_format($cajaDia, 2) }}</p>
                <span class="text-[11px] text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-md mt-2 inline-block">📈 Total acumulado</span>
            </div>
            <span class="text-3xl bg-slate-50 p-3 rounded-xl border border-slate-100">💵</span>
        </div>

        {{-- Tarjeta 2: Servicios Totales --}}
        <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Servicios Hoy</p>
                <p class="text-3xl font-black text-slate-900 mt-1">{{ $totalServiciosHoy }}</p>
                <span class="text-[11px] text-slate-500 mt-2 inline-block">Cortes finalizados</span>
            </div>
            <span class="text-3xl bg-slate-50 p-3 rounded-xl border border-slate-100">✂️</span>
        </div>

        {{-- Tarjeta 3: Barberos Conectados --}}
        <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Barberos Activos</p>
                <p class="text-3xl font-black text-slate-900 mt-1">{{ $barberosActivos }}</p>
                <span class="text-[11px] text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-md mt-2 inline-block">● Disponibles</span>
            </div>
            <span class="text-3xl bg-slate-50 p-3 rounded-xl border border-slate-100">👨‍🎨</span>
        </div>

        {{-- Tarjeta 4: Ticket Dinámico Promedio --}}
        <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Corte Promedio</p>
                <p class="text-3xl font-black text-slate-900 mt-1">${{ number_format($ticketPromedio, 2) }}</p>
                <span class="text-[11px] text-slate-500 mt-2 inline-block">Consumo por cliente</span>
            </div>
            <span class="text-3xl bg-slate-50 p-3 rounded-xl border border-slate-100">📊</span>
        </div>
    </div>

    {{-- 3. REPORTES DETALLADOS --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Últimos servicios ejecutados --}}
        <div class="lg:col-span-2 space-y-4">
            <h2 class="text-lg font-bold text-slate-900">Últimos Servicios en Silla</h2>

            <x-table>
                <thead>
                    <tr>
                        <x-table-th>Barbero</x-table-th>
                        <x-table-th>Servicio</x-table-th>
                        <x-table-th align="right">Precio Cobrado</x-table-th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($ultimasVentas as $venta)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3.5 px-6 font-semibold text-slate-900">👨‍🎨 {{ $venta->empleado->nombre ?? 'Sin asignar' }}</td>
                            <td class="py-3.5 px-6">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-800 text-xs font-bold rounded-lg">
                                    {{ $venta->servicio->nombre_servicio ?? 'Servicio Eliminado' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-right font-bold text-slate-900">${{ number_format($venta->precio_cobrado, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-12 text-center text-slate-400 text-sm">
                                Ningún barbero ha realizado cobros el día de hoy.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table>
        </div>

        {{-- Ranking de productividad del personal --}}
        <div class="space-y-4">
            <h2 class="text-lg font-bold text-slate-900">Rendimiento del Equipo</h2>
            
            <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm space-y-5">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Cortes por Barbero (Hoy)</p>
                
                @forelse($rankingBarberos as $barbero)
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-semibold text-slate-800">✨ {{ $barbero['nombre'] }}</span>
                            <span class="text-xs font-bold text-slate-500">{{ $barbero['total_cortes'] }} servicios</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-amber-500 h-full rounded-full transition-all duration-500" style="width: {{ $barbero['porcentaje'] }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-xs text-slate-400">Esperando el primer corte de la jornada...</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection