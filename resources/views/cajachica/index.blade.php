@extends('layouts.app')

@section('content')
<div x-data="{ editModalOpen: false, editRoute: '', editMonto: '' }" class="space-y-6">

    {{-- ALERTAS DE ÉXITO --}}
    @if(session('success'))
    <div class="p-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center gap-2 shadow-2xs">
        <span>✅</span> <span class="font-bold">{{ session('success') }}</span>
    </div>
    @endif

    {{-- ENCABEZADO --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 border border-slate-200 rounded-2xl shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-900">💸Historial de Cajas Chicas</h1>
            <p class="text-slate-500 text-xs mt-0.5">Auditoría, edición y control de fondos iniciales diarios.</p>
        </div>
        <a href="{{ route('cajachica.pdf') }}" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-black text-xs rounded-xl shadow-md flex items-center gap-1.5 transition-all hover:scale-105">
            📥 Descargar Reporte PDF
        </a>
    </div>

    {{-- TABLA DE REGISTROS --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-slate-700">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-2xs font-bold uppercase tracking-wider">
                        <th class="py-3 px-6">Fecha de Apertura</th>
                        <th class="py-3 px-6">Monto Inicial</th>
                        <th class="py-3 px-6 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($cajas as $caja)
                        <tr class="hover:bg-slate-50/40 transition-colors">
                            <td class="py-4 px-6 font-semibold text-slate-900">
                                📅 {{ date('d/m/Y', strtotime($caja->fecha)) }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 font-extrabold rounded-lg text-xs">
                                    ${{ number_format($caja->monto_inicial, 2) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right flex justify-end gap-2">
                                {{-- Botón Editar --}}
                                <button @click="editRoute = '{{ route('cajachica.update', $caja->id) }}'; editMonto = '{{ $caja->monto_inicial }}'; editModalOpen = true" 
                                        class="px-3 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 font-bold text-2xs rounded-lg transition-colors border border-slate-200 flex items-center gap-1 cursor-pointer">
                                    ✏️ Editar
                                </button>

                                {{-- Botón Eliminar --}}
                                <form action="{{ route('cajachica.destroy', $caja->id) }}" method="POST" onsubmit="return confirm('⚠️ ¿Estás seguro de eliminar este registro del historial?\n\nEsta acción no se puede deshacer.');">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white font-bold text-2xs rounded-lg transition-colors border border-red-200 flex items-center gap-1 cursor-pointer">
                                        🗑️ Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-12 text-center text-slate-400">
                                No hay registros de cajas chicas en el sistema.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Paginación --}}
        @if($cajas->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $cajas->links() }}
            </div>
        @endif
    </div>

    {{-- MODAL ORIGINAL DE EDICIÓN (Alpine.js) --}}
    <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs" @click="editModalOpen = false"></div>
        <div class="bg-white rounded-3xl p-6 max-w-sm w-full relative z-10 shadow-2xl border border-slate-100 space-y-4">
            <div class="text-center">
                <div class="mx-auto w-12 h-12 bg-indigo-50 text-indigo-600 text-xl rounded-full flex items-center justify-center font-bold mb-2">✏️</div>
                <h3 class="text-base font-black text-slate-900">Modificar Caja Chica</h3>
                <p class="text-2xs text-slate-400">Ajusta el monto inicial base seleccionado.</p>
            </div>

            <form :action="editRoute" method="POST" class="space-y-4">
                @csrf
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-sm font-bold text-slate-400">$</span>
                    <input type="number" step="0.01" name="monto_inicial" x-model="editMonto" required class="w-full pl-7 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 outline-none focus:border-indigo-500 transition-colors">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <button type="button" @click="editModalOpen = false" class="py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 cursor-pointer">Cancelar</button>
                    <button type="submit" class="py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-sm cursor-pointer">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection