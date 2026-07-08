@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    
    <div class="flex items-center gap-3">
        <a href="{{ route('ventas.index') }}" class="p-2 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-colors text-sm" title="Volver al historial">
            ⬅️ Volver
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Registrar Nuevo Servicio</h1>
            <p class="text-slate-500 text-sm mt-0.5">Introduce los detalles del corte o servicio cobrado en caja.</p>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Información del Ticket</h2>
        </div>

        <form action="#" method="POST" class="p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="empleado_id" class="block text-sm font-semibold text-slate-700 mb-1.5">👨‍🎨 Barbero que Atendió</label>
                    <select name="empleado_id" id="empleado_id" required class="w-full rounded-xl border border-slate-200 p-2.5 bg-white text-slate-800 text-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none transition-all">
                        <option value="" disabled selected>Selecciona un barbero...</option>
                        @foreach($barberos as $barbero)
                            <option value="{{ $barbero->id }}">{{ $barbero->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="servicio_id" class="block text-sm font-semibold text-slate-700 mb-1.5">✂️ Servicio Solicitado</label>
                    <select name="servicio_id" id="servicio_id" required class="w-full rounded-xl border border-slate-200 p-2.5 bg-white text-slate-800 text-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none transition-all">
                        <option value="" disabled selected>Selecciona un servicio...</option>
                        @foreach($servicios as $servicio)
                            <option value="{{ $servicio->id }}">
                                {{ $servicio->nombre_servicio }} (${{ number_format($servicio->precio, 2) }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pt-2">
                <div>
                    <label for="precio_cobrado" class="block text-sm font-semibold text-slate-700 mb-1.5">💰 Precio Real Cobrado ($)</label>
                    <input type="number" step="0.01" name="precio_cobrado" id="precio_cobrado" required placeholder="0.00" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm font-bold text-slate-900 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none transition-all">
                    <span class="text-[11px] text-slate-400 mt-1 block">Modifica si se aplicó un descuento en caja.</span>
                </div>

                <div>
                    <label for="fecha" class="block text-sm font-semibold text-slate-700 mb-1.5">📅 Fecha</label>
                    <input type="date" name="fecha" id="fecha" required value="{{ date('Y-m-d') }}" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm text-slate-800 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none transition-all">
                </div>

                <div>
                    <label for="hora" class="block text-sm font-semibold text-slate-700 mb-1.5">🕒 Hora</label>
                    <input type="time" name="hora" id="hora" required value="{{ date('H:i') }}" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm text-slate-800 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none transition-all">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('ventas.index') }}" class="px-4 py-2.5 border border-slate-200 text-slate-600 font-medium rounded-xl hover:bg-slate-50 text-sm transition-all">
                    Cancelar
                </a>
                <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-sm transition-all shadow-md shadow-amber-500/10">
                    💾 Guardar Registro
                </a>
            </div>
        </form>
    </div>
</div>