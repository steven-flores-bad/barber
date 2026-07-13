@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-xl mx-auto">
    <div class="flex justify-between items-center bg-white p-6 border border-slate-200 rounded-2xl shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-900">💵 Cierre de Caja</h1>
            <p class="text-slate-500 text-xs mt-0.5">Resumen automatizado de ingresos diarios.</p>
        </div>
        <form action="{{ route('reportes.cierre') }}" method="GET">
            <input type="date" name="fecha" value="{{ $fecha }}" onchange="this.form.submit()" class="rounded-xl border border-slate-200 p-2.5 bg-white text-slate-800 text-xs font-bold outline-none focus:border-amber-500 shadow-2xs">
        </form>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
        <div class="text-center py-6 bg-slate-50 rounded-xl border border-slate-100">
            <span class="text-2xs font-bold text-slate-400 uppercase tracking-widest block mb-1">Total Recaudado en Cortes</span>
            <span class="text-4xl font-black text-emerald-600">${{ number_format($granTotal, 2) }}</span>
        </div>

        <div class="flex justify-between items-center p-4 border border-slate-100 rounded-xl bg-slate-50/30">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold">✂️</div>
                <div>
                    <span class="font-bold text-slate-800 text-sm block">Volumen de Trabajo</span>
                    <span class="text-2xs text-slate-400">Total de servicios completados hoy</span>
                </div>
            </div>
            <span class="font-black text-slate-900 text-base">{{ $totalCortes }} atendidos</span>
        </div>

        <div class="pt-2 flex justify-end">
    <a href="{{ route('reportes.cierre.pdf', ['fecha' => $fecha]) }}" class="px-4 py-2.5 bg-slate-900 text-white font-bold text-xs rounded-xl hover:bg-slate-800 transition-colors flex items-center gap-1.5 cursor-pointer shadow-xs">
        📥 Descargar Cierre de Turno Oficial
    </a>
</div>
    </div>
</div>
@endsection