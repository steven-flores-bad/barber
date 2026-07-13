@extends('layouts.app')

@section('content')
<div class="space-y-8">

{{-- MENSAJE FLOTANTE DE ÉXITO --}}
    @if(session('success'))
    <div class="p-4 mb-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center gap-2 shadow-xs transition-all animate-fade-in">
        <span>✅</span>
        <span class="font-bold">{{ session('success') }}</span>
    </div>
    @endif
    
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
        
     {{-- Tarjeta 1: Fondo Fijo de Caja Chica --}}
<div class="bg-gradient-to-br from-indigo-50 to-white border-2 border-indigo-200 p-5 rounded-2xl shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow min-h-[135px]">
    <div class="flex justify-between items-start w-full">
        <div>
            <p class="text-xs font-bold text-indigo-700 uppercase tracking-wider">🪙 Caja Chica Inicial</p>
            <p class="text-3xl font-black text-indigo-600 mt-1">
                ${{ number_format($cajaChicaHoy, 2) }}
            </p>
        </div>
        <span class="text-2xl bg-indigo-500/10 p-2 rounded-xl border border-indigo-100">🪙</span>
    </div>

    @if($cajaChicaHoy == 0)
        <form action="{{ route('reportes.cajachica.guardar') }}" method="POST" class="flex gap-1 mt-2">
            @csrf
            <div class="relative flex-1">
                <span class="absolute left-2 top-1 text-2xs font-bold text-slate-400">$</span>
                <input type="number" step="0.01" name="monto_inicial" placeholder="0.00" required class="w-full pl-4 pr-1 py-1 bg-white border border-slate-200 rounded-lg text-2xs font-bold text-slate-800 outline-none focus:border-indigo-500 transition-all">
            </div>
            <button type="submit" class="px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-[10px] rounded-lg transition-colors cursor-pointer shadow-xs">
                Definir
            </button>
        </form>
    @else
        <div class="flex items-center justify-between mt-2 w-full">
            <div class="text-[10px] text-indigo-600 font-black uppercase tracking-wider flex items-center gap-1 bg-indigo-50/50 py-1 px-2 rounded-md border border-indigo-100 w-fit">
                ● Turno Abierto
            </div>
            
            <!-- Botón que activa el Modal Original -->
            <button type="button" onclick="document.getElementById('modal-eliminar-caja').classList.remove('hidden')" class="text-red-600 hover:text-white text-xs font-bold flex items-center gap-1.5 transition-all duration-200 cursor-pointer bg-red-50 hover:bg-red-600 px-2.5 py-1.5 rounded-xl border border-red-200 hover:border-red-600 shadow-2xs hover:scale-105">
    🗑️ Corregir Monto
</button>
        </div>
    @endif
</div>

        {{-- Tarjeta 2: Caja Total (Suma de Ventas + Caja Chica) --}}
        <div class="bg-gradient-to-br from-emerald-950 via-slate-900 to-slate-950 text-white p-5 rounded-2xl shadow-md flex flex-col justify-between hover:shadow-lg transition-all min-h-[135px] border border-emerald-800">
            <div class="flex justify-between items-start w-full">
                <div>
                    <p class="text-xs font-bold text-emerald-400 uppercase tracking-wider">💵 Caja Total (Hoy)</p>
                    <p class="text-3xl font-black text-emerald-400 mt-1 tracking-tight">
                        ${{ number_format($cajaDia + $cajaChicaHoy, 2) }}
                    </p>
                </div>
                <span class="text-2xl bg-emerald-500/10 p-2 rounded-xl border border-emerald-800 text-emerald-400">💵</span>
            </div>
            
            <div class="mt-2 pt-2 border-t border-emerald-800/60 text-[10px] text-slate-300 font-bold uppercase tracking-wider flex justify-between">
                <span>Ventas: <strong class="text-white">${{ number_format($cajaDia, 2) }}</strong></span>
                <span>Fondo: <strong class="text-white">${{ number_format($cajaChicaHoy, 2) }}</strong></span>
            </div>
        </div>

        {{-- Tarjeta 3: Servicios Totales --}}
        <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Servicios Hoy</p>
                <p class="text-3xl font-black text-slate-900 mt-1">{{ $totalServiciosHoy }}</p>
                <span class="text-[11px] text-slate-500 mt-2 inline-block">Cortes finalizados</span>
            </div>
            <span class="text-3xl bg-slate-50 p-3 rounded-xl border border-slate-100">✂️</span>
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
{{-- Tu código anterior del dashboard... --}}

    <!-- Inclusión limpia del archivo del modal -->
    @include('dashboard.modal_eliminar_caja')

</div>

@endsection