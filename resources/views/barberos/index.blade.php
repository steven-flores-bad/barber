@extends('layouts.app')

@section('content')
<div x-data="{ 
    openModal: false, 
    openEditModal: false,
    openDeleteModal: false,
    editRoute: '',
    deleteRoute: '',
    editNombre: '',
    editTelefono: '',
    editEstado: '1',
    
    prepararEdicion(id, nombre, telefono, estado, urlBase) {
        this.editRoute = urlBase + '/' + id;
        this.editNombre = nombre;
        this.editTelefono = telefono || '';
        this.editEstado = estado;
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
            <h1 class="text-2xl font-bold text-slate-900">Equipo de Barberos</h1>
            <p class="text-slate-500 text-sm mt-0.5">Administra el personal, sus datos de contacto y sus estados de acceso.</p>
        </div>
        <button @click="openModal = true" class="px-4 py-2.5 bg-slate-900 text-white font-medium rounded-xl hover:bg-slate-800 transition-all text-sm shadow-sm flex items-center gap-2 cursor-pointer">
            <span>➕</span> Agregar Barbero
        </button>
    </div>

    <form action="{{ route('barberos.index') }}" method="GET" class="bg-white p-4 border border-slate-200 rounded-2xl shadow-sm flex gap-3 items-center">
    <div class="w-full max-w-xs">
        <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar barbero por nombre..." class="w-full rounded-xl border border-slate-100 p-2.5 bg-white text-slate-500 text-sm outline-none focus:border-amber-500 transition-all">
    </div>
        <button type="submit" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition-colors cursor-pointer">
            🔍 Buscar
        </button>
        @if(request()->has('buscar'))
            <a href="{{ route('barberos.index') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition-colors text-center">❌ Limpiar</a>
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
                        <a href="{{ sort_table_link('nombre') }}" class="hover:text-slate-900 inline-flex items-center gap-1">
                            Nombre del Barbero {!! sort_table_icon('nombre') !!}
                        </a>
                    </th>
                    
                    <th class="py-4 px-6">
                        <a href="{{ sort_table_link('telefono') }}" class="hover:text-slate-900 inline-flex items-center gap-1">
                            Teléfono de Contacto {!! sort_table_icon('telefono') !!}
                        </a>
                    </th>
                    
                    <th class="py-4 px-6 text-center w-32">
                        <a href="{{ sort_table_link('estado') }}" class="hover:text-slate-900 inline-flex items-center gap-1 justify-center w-full">
                            Estado {!! sort_table_icon('estado') !!}
                        </a>
                    </th>
                    
                    <th class="py-4 px-6 text-center w-28">Acciones</th>
                </tr>
            </thead>
            
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($empleados as $b)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 px-6 text-center font-bold text-slate-500 bg-slate-50/50">#{{ $b->id }}</td>
                            <td class="py-4 px-6 font-semibold text-slate-900 whitespace-nowrap">👨‍🎨 {{ $b->nombre }}</td>
                            <td class="py-4 px-6 text-slate-600 whitespace-nowrap">{{ $b->telefono ?? 'Sin teléfono' }}</td>
                            
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                @if($b->estado)
                                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-lg border border-emerald-200">Activo</span>
                                @else
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-500 text-xs font-bold rounded-lg border border-slate-200">Inactivo</span>
                                @endif
                            </td>
                            
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <div class="flex justify-center items-center gap-1.5">
                                    <button 
                                        @click="prepararEdicion({{ $b->id }}, '{{ $b->nombre }}', '{{ $b->telefono }}', '{{ $b->estado }}', '{{ url('/barberos/actualizar') }}')"
                                        title="Editar datos" class="p-1.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-600 rounded-lg cursor-pointer"
                                    >
                                        ✏️
                                    </button>
                                    <button 
                                        @click="prepararEliminacion({{ $b->id }}, '{{ url('/barberos/eliminar') }}')"
                                        title="Eliminar del sistema" class="p-1.5 bg-red-50 hover:bg-red-100 border border-red-100 text-red-500 rounded-lg cursor-pointer"
                                    >
                                        🗑️
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-12 text-center text-slate-400">No hay barberos registrados en el sistema.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

          {{-- Paginación Extraída Limpiamente --}}
    <x-pagination :coleccion="$empleados" />


    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;">
        <div @click.away="openModal = false" x-show="openModal" class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">👨‍¼ Contratar Nuevo Barbero</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Ingresa los datos personales del empleado.</p>
                </div>
                <button @click="openModal = false" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-200 hover:text-slate-700 font-bold cursor-pointer">✕</button>
            </div>
            <form action="{{ route('barberos.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label for="nombre" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nombre Completo</label>
                    <input type="text" name="nombre" id="nombre" required placeholder="Ej: Juan Pérez" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm font-medium text-slate-900 focus:border-amber-500 outline-none">
                </div>
                <div>
                    <label for="telefono" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Teléfono Movil</label>
                    <input type="text" name="telefono" id="telefono" placeholder="Ej: 7777-1234" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm font-medium text-slate-900 focus:border-amber-500 outline-none">
                </div>
                <div>
                    <label for="estado" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Estado Inicial</label>
                    <select name="estado" id="estado" required class="w-full rounded-xl border border-slate-200 p-2.5 bg-white text-sm focus:border-amber-500 outline-none">
                        <option value="1">Activo (Disponible para trabajar)</option>
                        <option value="0">Inactivo (No disponible)</option>
                    </select>
                </div>
                <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" @click="openModal = false" class="px-4 py-2.5 border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 text-sm cursor-pointer">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-black rounded-xl text-sm shadow-md cursor-pointer">💾 Guardar Contrato</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="openEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;">
        <div @click.away="openEditModal = false" x-show="openEditModal" class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">✏️ Editar Ficha de Empleado</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Modifica los detalles del barbero seleccionado.</p>
                </div>
                <button @click="openEditModal = false" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-200 hover:text-slate-700 font-bold cursor-pointer">✕</button>
            </div>
            <form :action="editRoute" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label for="edit_nombre" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nombre Completo</label>
                    <input type="text" name="nombre" id="edit_nombre" required x-model="editNombre" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm font-bold text-slate-900 focus:border-amber-500 outline-none">
                </div>
                <div>
                    <label for="edit_telefono" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Teléfono Movil</label>
                    <input type="text" name="telefono" id="edit_telefono" x-model="editTelefono" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm font-medium text-slate-900 focus:border-amber-500 outline-none">
                </div>
                <div>
                    <label for="edit_estado" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Disponibilidad en Caja</label>
                    <select name="estado" id="edit_estado" x-model="editEstado" class="w-full rounded-xl border border-slate-200 p-2.5 bg-white text-sm focus:border-amber-500 outline-none">
                        <option value="1">Activo (Aparece en el menú de cobros)</option>
                        <option value="0">Inactivo (Oculto en módulo de ventas)</option>
                    </select>
                </div>
                <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" @click="openEditModal = false" class="px-4 py-2.5 border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 text-sm cursor-pointer">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-black rounded-xl text-sm shadow-md cursor-pointer">💾 Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="openDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;">
        <div @click.away="openDeleteModal = false" x-show="openDeleteModal" class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col">
            <div class="p-6 text-center space-y-4">
                <div class="w-12 h-12 rounded-full bg-red-50 border border-red-200 text-red-500 text-xl font-bold flex items-center justify-center mx-auto">⚠️</div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">¿Despedir o borrar barbero?</h2>
                    <p class="text-sm text-slate-500 mt-1">Esta acción es permanente. Si el empleado tiene un historial de ventas antiguo, el sistema impedirá el borrado para proteger tus reportes de caja.</p>
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