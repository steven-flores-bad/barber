@extends('layouts.app')

@section('content')
<div x-data="{ 
    openModal: false, 
    openEditModal: false,
    openDeleteModal: false,
    editRoute: '',
    deleteRoute: '',
    editNombre: '',
    editPrecio: '',
    
    prepararEdicion(id, nombre, precio, urlBase) {
        this.editRoute = urlBase + '/' + id;
        this.editNombre = nombre;
        this.editPrecio = precio;
        this.openEditModal = true;
    },
    prepararEliminacion(id, urlBase) {
        this.deleteRoute = urlBase + '/' + id;
        this.openDeleteModal = true;
    }
}" class="space-y-6 relative">
    
    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200 font-medium flex items-center gap-2">
            <span>✅</span> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-xl bg-red-50 border border-red-200 font-medium flex items-center gap-2">
            <span>⚠️</span> {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Catálogo de Servicios</h1>
            <p class="text-slate-500 text-sm mt-0.5">Gestiona los tipos de cortes, tratamientos y los precios base del negocio.</p>
        </div>
        <button @click="openModal = true" class="px-4 py-2.5 bg-slate-900 text-white font-medium rounded-xl hover:bg-slate-800 transition-all text-sm shadow-sm flex items-center gap-2 cursor-pointer">
            <span>➕</span> Nuevo Servicio
        </button>
    </div>

    <form action="{{ route('servicios.index') }}" method="GET" class="max-w-xl bg-white p-4 border border-slate-400 rounded-2xl shadow-sm flex gap-3 items-center">
    <div class="flex-1">
        <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar servicio por nombre (Ej: Tinte, Barba)..." class="w-full rounded-xl border border-slate-200 p-2.5 bg-white text-slate-800 text-sm outline-none focus:border-amber-500 transition-all">
    </div>
    <button type="submit" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition-colors cursor-pointer">
        🔍 Buscar
    </button>
    @if(request()->has('buscar'))
        <a href="{{ route('servicios.index') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition-colors text-center">❌ Limpiar</a>
    @endif
</form>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-4 px-6 w-12 text-center">
                            <a href="{{ sort_table_link('id') }}" class="hover:text-slate-900 inline-flex items-center gap-1">
                            ID {!! sort_table_icon('id') !!}
                        </a>
                        </th>

                        <th class="py-4 px-6">
                            <a href="{{ route('servicios.index', array_merge(request()->query(), ['sort' => 'nombre_servicio', 'direction' => request('sort') == 'nombre_servicio' || !request('sort') ? (request('direction') == 'asc' || !request('direction') ? 'desc' : 'asc') : 'asc'])) }}" class="hover:text-slate-900 inline-flex items-center gap-1">
                                Descripción del Servicio {!! sort_table_icon('nombre_servicio') !!}
                            </a>
                        </th>
                        <th class="py-4 px-6 text-right w-44">
                            <a href="{{ route('servicios.index', array_merge(request()->query(), ['sort' => 'precio', 'direction' => request('sort') == 'precio' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="hover:text-slate-900 inline-flex items-center gap-1 justify-end w-full">
                                Precio Base {!! sort_table_icon('Precio') !!}
                            </a>
                        </th>
                        <th class="py-4 px-6 text-center w-28">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($servicios as $s)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 px-6 text-center font-bold text-slate-500 bg-slate-50/50">
                                {{ ($servicios->currentPage() - 1) * $servicios->perPage() + $loop->iteration }}
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-900 whitespace-nowrap">✂️ {{ $s->nombre_servicio }}</td>
                            <td class="py-4 px-6 text-right font-bold text-slate-900 whitespace-nowrap">${{ number_format($s->precio, 2) }}</td>
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <div class="flex justify-center items-center gap-1.5">
                                    <button 
                                        @click="prepararEdicion({{ $s->id }}, '{{ $s->nombre_servicio }}', '{{ $s->precio }}', '{{ url('/servicios/actualizar') }}')"
                                        title="Editar servicio" class="p-1.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-600 rounded-lg cursor-pointer"
                                    >
                                        ✏️
                                    </button>
                                    <button 
                                        @click="prepararEliminacion({{ $s->id }}, '{{ url('/servicios/eliminar') }}')"
                                        title="Eliminar del catálogo" class="p-1.5 bg-red-50 hover:bg-red-100 border border-red-100 text-red-500 rounded-lg cursor-pointer"
                                    >
                                        🗑️
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-12 text-center text-slate-400">No hay servicios registrados con esos criterios.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($servicios->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex items-center justify-between text-xs text-slate-500">
                <div>Mostrando del <span class="font-bold text-slate-700">{{ $servicios->firstItem() }}</span> al <span class="font-bold text-slate-700">{{ $servicios->lastItem() }}</span> de <span class="font-bold text-slate-700">{{ $servicios->total() }}</span> registros</div>
                <div class="flex gap-2">
                    @if($servicios->onFirstPage()) <span class="px-3 py-2 bg-slate-200/60 text-slate-400 rounded-xl font-semibold cursor-not-allowed">◀️ Anterior</span>
                    @else <a href="{{ $servicios->previousPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-slate-50">◀️ Anterior</a> @endif
                    @if($servicios->hasMorePages()) <a href="{{ $servicios->nextPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-slate-50">Siguiente ▶️</a>
                    @else <span class="px-3 py-2 bg-slate-200/60 text-slate-400 rounded-xl font-semibold cursor-not-allowed">Siguiente ▶️</span> @endif
                </div>
            </div>
        @endif
    </div>

    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;">
        <div @click.away="openModal = false" x-show="openModal" class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">💈 Crear Nuevo Servicio</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Agrega un nuevo ítem o tarifa al catálogo general.</p>
                </div>
                <button @click="openModal = false" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-200 hover:text-slate-700 font-bold cursor-pointer">✕</button>
            </div>
            <form action="{{ route('servicios.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label for="nombre_servicio" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nombre del Servicio / Corte</label>
                    <input type="text" name="nombre_servicio" id="nombre_servicio" required placeholder="Ej: Combo: Corte + Barba + Lavado" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm font-medium text-slate-900 focus:border-amber-500 outline-none">
                </div>
                <div>
                    <label for="precio" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Precio Base al Público ($)</label>
                    <input type="number" step="0.01" min="0" name="precio" id="precio" required placeholder="0.00" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm font-bold text-slate-900 focus:border-amber-500 outline-none">
                </div>
                <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" @click="openModal = false" class="px-4 py-2.5 border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 text-sm cursor-pointer">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-black rounded-xl text-sm shadow-md cursor-pointer">💾 Guardar Servicio</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="openEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;">
        <div @click.away="openEditModal = false" x-show="openEditModal" class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">✏️ Modificar Servicio</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Ajusta la descripción o la tarifa del ítem elegido.</p>
                </div>
                <button @click="openEditModal = false" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-200 hover:text-slate-700 font-bold cursor-pointer">✕</button>
            </div>
            <form :action="editRoute" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label for="edit_nombre_servicio" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nombre del Servicio / Corte</label>
                    <input type="text" name="nombre_servicio" id="edit_nombre_servicio" required x-model="editNombre" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm font-bold text-slate-900 focus:border-amber-500 outline-none">
                </div>
                <div>
                    <label for="edit_precio" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Precio Base al Público ($)</label>
                    <input type="number" step="0.01" min="0" name="precio" id="edit_precio" required x-model="editPrecio" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm font-bold text-slate-900 focus:border-amber-500 outline-none">
                </div>
                <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" @click="openEditModal = false" class="px-4 py-2.5 border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 text-sm cursor-pointer">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-black rounded-xl text-sm shadow-md cursor-pointer">💾 Actualizar Tarifas</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="openDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;">
        <div @click.away="openDeleteModal = false" x-show="openDeleteModal" class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col">
            <div class="p-6 text-center space-y-4">
                <div class="w-12 h-12 rounded-full bg-red-50 border border-red-200 text-red-500 text-xl font-bold flex items-center justify-center mx-auto">⚠️</div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">¿Remover del catálogo?</h2>
                    <p class="text-sm text-slate-500 mt-1">Esta acción es permanente. Para proteger los balances y el historial contable, el sistema detendrá la eliminación si este servicio ya fue cobrado en el pasado.</p>
                </div>
            </div>
            <form :action="deleteRoute" method="POST" class="px-6 pb-6 pt-2 flex items-center gap-3">
                @csrf
                <button type="button" @click="openDeleteModal = false" class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 text-sm cursor-pointer text-center">Cancelar</button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-sm shadow-md cursor-pointer text-center">Sí, Eliminar</button>
            </form>
        </div>
    </div>

</div>
@endsection