@extends('layouts.app')

@section('content')
<div class="space-y-6 relative">
    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 border border-slate-200 rounded-2xl shadow-sm">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">📊 Panel de Reportes</h1>
            <p class="text-sm text-slate-500 mt-0.5">Monitorea los cortes realizados y las ganancias acumuladas.</p>
        </div>
        
        <form action="{{ route('reportes.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <input type="date" name="fecha" value="{{ $fecha }}" class="rounded-xl border border-slate-200 p-2.5 bg-white text-slate-800 text-sm outline-none focus:border-amber-500 transition-all">
            <button type="submit" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm rounded-xl transition-colors cursor-pointer">
                🔍 Filtrar
            </button>
            <a href="{{ route('reportes.pdf', ['fecha' => $fecha]) }}" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-black text-sm rounded-xl transition-colors inline-flex items-center gap-2 shadow-sm">
                📥 Descargar PDF
            </a>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="p-4 bg-amber-50 rounded-xl text-amber-600 text-2xl">✂️</div>
            <div>
                <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Servicios Totales</span>
                <span class="text-3xl font-bold text-slate-900">{{ $totalCortesDia }} servicios</span>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="p-4 bg-emerald-50 rounded-xl text-emerald-600 text-2xl">💵</div>
            <div>
                <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Caja Total del Día</span>
                <span class="text-3xl font-bold text-slate-900">${{ number_format($totalDineroDia, 2) }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-bold text-slate-900 text-sm uppercase tracking-wider text-slate-500">Performance de Barberos</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-4 px-6">Nombre del Barbero</th>
                        <th class="py-4 px-6 text-center w-40">Cant. Cortes</th>
                        <th class="py-4 px-6 text-right w-52">Subtotal Recaudado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($resumenBarberos as $rb)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-4 px-6 font-semibold text-slate-900">💈 {{ $rb->barbero }}</td>
                        <td class="py-4 px-6 text-center font-bold bg-slate-50/30">{{ $rb->total_cortes }}</td>
                        <td class="py-4 px-6 text-right font-bold text-emerald-600">${{ number_format($rb->total_recaudado, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-12 text-center text-slate-400">No hay ventas registradas en esta fecha.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($ventasPorBarbero->count() > 0)
    <div class="space-y-4">
        <h2 class="text-base font-bold text-slate-900 uppercase tracking-wider text-slate-500">📝 Desglose de Operaciones</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($ventasPorBarbero as $barbero => $servicios)
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 space-y-3">
                <div class="flex justify-between items-center border-b border-slate-100 pb-2">
                    <h4 class="font-bold text-slate-900 text-sm">💇‍♂️ {{ $barbero }}</h4>
                    <span class="text-xs bg-slate-100 px-2.5 py-1 rounded-full font-bold text-slate-600">{{ count($servicios) }} servicios</span>
                </div>
                <div class="divide-y divide-slate-50 text-sm max-h-60 overflow-y-auto pr-1">
                    @foreach($servicios as $s)
                    <div class="flex justify-between items-center py-2.5 text-slate-600">
                        <div>
                            <span class="font-semibold text-slate-800 text-sm">{{ $s->servicio }}</span>
                            <span class="text-xs text-slate-400 block font-medium">{{ date('h:i A', strtotime($s->hora)) }}</span>
                        </div>
                        <span class="font-bold text-slate-900">${{ number_format($s->precio_cobrado, 2) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection