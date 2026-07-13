@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 border border-slate-200 rounded-2xl shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-900">✂️ Auditoría de Servicios</h1>
            <p class="text-slate-500 text-xs mt-0.5">Listado detallado de cada corte o servicio cobrado en caja.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <form action="{{ route('reportes.ventas') }}" method="GET" class="flex items-center gap-2">
                <input type="date" name="desde" value="{{ $desde }}" class="rounded-xl border border-slate-200 p-2 text-xs font-semibold text-slate-700 outline-none focus:border-amber-500">
                <input type="date" name="hasta" value="{{ $hasta }}" class="rounded-xl border border-slate-200 p-2 text-xs font-semibold text-slate-700 outline-none focus:border-amber-500">
                <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl cursor-pointer">Filtrar</button>
            </form>
            {{-- Modificado únicamente el name de la ruta para que use el archivo exclusivo del PDF --}}
            <a href="{{ route('reportes.ventas.pdf', ['desde' => $desde, 'hasta' => $hasta]) }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-black text-xs rounded-xl shadow-xs">📥 Descargar PDF</a>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-4 px-6 text-center w-24">Ticket</th>
                        <th class="py-4 px-6">Fecha / Hora</th>
                        <th class="py-4 px-6">Servicio</th>
                        <th class="py-4 px-6">Barbero Atendió</th>
                        <th class="py-4 px-6 text-right w-40">Precio Cobrado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($ventas as $v)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 text-center font-bold text-slate-400">#{{ $v->ticket_id }}</td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="font-semibold text-slate-800 block">{{ date('d/m/Y', strtotime($v->fecha)) }}</span>
                                <span class="text-2xs text-slate-400 font-medium block">{{ date('h:i A', strtotime($v->hora)) }}</span>
                            </td>
                            <td class="py-4 px-6 font-bold text-slate-900">💈 {{ $v->nombre_servicio }}</td>
                            <td class="py-4 px-6 font-semibold text-slate-600">👨‍🎨 {{ $v->barbero }}</td>
                            <td class="py-4 px-6 text-right font-black text-emerald-600">${{ number_format($v->precio_cobrado, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-12 text-center text-slate-400">No hay registros de caja para este rango de fechas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection