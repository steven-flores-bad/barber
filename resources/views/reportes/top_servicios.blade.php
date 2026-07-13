@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center bg-white p-6 border border-slate-200 rounded-2xl shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-900">🔥 Ranking de Servicios Estrellas (Top 10)</h1>
            <p class="text-slate-500 text-xs mt-0.5">Analiza los servicios con mayor rotación e impacto financiero.</p>
        </div>
        <a href="{{ route('reportes.pdf', ['tipo' => 'top']) }}" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-black text-xs rounded-xl shadow-xs">📥 Descargar PDF</a>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="py-4 px-6 w-20 text-center">Posición</th>
                    <th class="py-4 px-6">Servicio</th>
                    <th class="py-4 px-6 text-center w-48">Cantidad Realizada</th>
                    <th class="py-4 px-6 text-right w-52">Subtotal Recaudado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                @forelse($topServicios as $index => $ts)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg text-xs font-bold 
                                {{ $index == 0 ? 'bg-amber-100 text-amber-800' : ($index == 1 ? 'bg-slate-200 text-slate-800' : 'bg-slate-100 text-slate-500') }}">
                                {{ $index + 1 }}
                            </span>
                        </td>
                        <td class="py-4 px-6 font-bold text-slate-900">💈 {{ $ts->nombre_servicio }}</td>
                        <td class="py-4 px-6 text-center font-semibold bg-slate-50/40">{{ $ts->cantidad_vendida }} veces</td>
                        <td class="py-4 px-6 text-right font-black text-emerald-600">${{ number_format($ts->ingresos_totales, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-12 text-center text-slate-400">Sin datos de volumen de servicios históricos.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection