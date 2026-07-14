@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    
    {{-- ENCABEZADO Y FILTROS --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 border border-slate-200 rounded-2xl shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-900">👨‍🎨 Rendimiento del Equipo</h1>
            <p class="text-slate-500 text-xs mt-0.5">Métricas de productividad y volumen de servicios por barbero.</p>
        </div>
        
        {{-- Selector de Período Dinámico y Botón PDF --}}
        <form action="{{ route('reportes.barberos') }}" method="GET" class="flex items-center gap-2">
            <select name="periodo" onchange="this.form.submit()" class="rounded-xl border border-slate-200 p-2.5 bg-white text-slate-800 text-xs font-bold outline-none focus:border-amber-500 shadow-2xs">
                <option value="dia" {{ $filtro === 'dia' ? 'selected' : '' }}>Hoy</option>
                <option value="semana" {{ $filtro === 'semana' ? 'selected' : '' }}>Esta Semana</option>
                <option value="mes" {{ $filtro === 'mes' ? 'selected' : '' }}>Este Mes</option>
            </select>
            
            <a href="{{ route('reportes.pdf', ['tipo' => 'barberos', 'periodo' => $filtro]) }}" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-md flex items-center gap-1.5 transition-all">
                📥 PDF
            </a>
        </form>
    </div>

    {{-- CARDS DE RESUMEN RÁPIDO --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($resumenBarberos as $rb)
            <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $rb['barbero'] }}</p>
                    <p class="text-2xl font-black text-slate-900 mt-1">${{ number_format($rb['total_recaudado'], 2) }}</p>
                    <span class="text-[11px] text-amber-600 font-bold bg-amber-50 px-2 py-0.5 rounded-md mt-2 inline-block">
                        ✂️ {{ $rb['total_servicios'] }} Cortes realizados
                    </span>
                </div>
                <span class="text-3xl bg-slate-50 p-3 rounded-xl border border-slate-100">💈</span>
            </div>
        @endforeach
    </div>

    {{-- DESGLOSE DETALLADO DE TRANSACCIONES POR BARBERO --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">📋 Historial de Servicios del Período</h2>
        </div>

        <div class="divide-y divide-slate-200">
            @forelse($ventasPorBarbero as $barbero => $servicios)
                <div class="p-6 space-y-3">
                    <div class="flex justify-between items-center">
                        <h3 class="font-black text-slate-900 text-base flex items-center gap-2">
                            <span>👨‍🎨</span> {{ $barbero }}
                        </h3>
                        <span class="text-xs font-bold text-slate-400">
                            Total transacciones: {{ $servicios->count() }}
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs text-slate-600">
                            <thead>
                                <tr class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                                    <th class="py-2.5 px-4">Fecha</th>
                                    <th class="py-2.5 px-4">Hora</th>
                                    <th class="py-2.5 px-4">Servicio Realizado</th>
                                    <th class="py-2.5 px-4 text-right">Monto Cobrado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($servicios as $s)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="py-2.5 px-4">📅 {{ date('d/m/Y', strtotime($s->fecha)) }}</td>
                                        <td class="py-2.5 px-4">⏰ {{ date('h:i A', strtotime($s->hora)) }}</td>
                                        <td class="py-2.5 px-4 font-semibold text-slate-800">{{ $s->nombre_servicio }}</td>
                                        <td class="py-2.5 px-4 text-right font-black text-emerald-600">${{ number_format($s->precio_cobrado, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-slate-400 text-sm">
                    No se registran transacciones ni servicios en este periodo para el equipo.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection