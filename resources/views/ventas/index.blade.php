@extends('layouts.app')

@section('content')
<div x-data="{ 
    openModal: false, 
    openEditModal: false,
    openDeleteModal: false,
    editRoute: '',
    deleteRoute: '',
    editEmpleado: '',
    editServicio: '',
    editPrecio: '',
    
    prepararEdicion(id, empleadoId, servicioId, precio, urlBase) {
        this.editRoute = urlBase + '/' + id;
        this.editEmpleado = empleadoId;
        this.editServicio = servicioId;
        this.editPrecio = precio;
        this.openEditModal = true;
    },
    prepararEliminacion(id, urlBase) {
        // Carga la ruta exacta de eliminación para el formulario de confirmación
        this.deleteRoute = urlBase + '/' + id;
        this.openDeleteModal = true;
    }
}" class="space-y-6 relative">
    
    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200 font-medium flex items-center gap-2">
            <span>✅</span> {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Historial de Ventas</h1>
            <p class="text-slate-500 text-sm mt-0.5">Lista de todos los servicios y cortes registrados en el sistema.</p>
        </div>
        <button @click="openModal = true" class="px-4 py-2.5 bg-slate-900 text-white font-medium rounded-xl hover:bg-slate-800 transition-all text-sm shadow-sm flex items-center gap-2 cursor-pointer">
            <span>➕</span> Registrar Corte
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-amber-500 text-slate-950 p-5 rounded-2xl shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider opacity-70">Total Facturado Recaudado</p>
                <p class="text-3xl font-black mt-1">${{ number_format($totalGanancias, 2) }}</p>
            </div>
            <span class="text-3xl opacity-30">💰</span>
        </div>
        <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Servicios Atendidos</p>
                <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $ventas->total() }}</p>
            </div>
            <span class="text-3xl text-slate-300">💈</span>
        </div>
    </div>

    <form action="{{ route('ventas.index') }}" method="GET" class="bg-white p-4 border border-slate-200 rounded-2xl shadow-sm grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="filter_barbero" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Buscar por Barbero</label>
            <select name="barbero_id" id="filter_barbero" class="w-full rounded-xl border border-slate-200 p-2 bg-white text-slate-700 text-sm outline-none focus:border-amber-500">
                <option value="">Todos los barberos</option>
                @foreach($barberos as $b) 
                    <option value="{{ $b->id }}" {{ request('barbero_id') == $b->id ? 'selected' : '' }}>{{ $b->nombre }}</option> 
                @endforeach
            </select>
        </div>
        <div>
            <label for="filter_servicio" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Filtrar por Servicio</label>
            <select name="servicio_id" id="filter_servicio" class="w-full rounded-xl border border-slate-200 p-2 bg-white text-slate-700 text-sm outline-none focus:border-amber-500">
                <option value="">Todos los servicios</option>
                @foreach($servicios as $s) 
                    <option value="{{ $s->id }}" {{ request('servicio_id') == $s->id ? 'selected' : '' }}>{{ $s->nombre_servicio }}</option> 
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm p-2 rounded-xl transition-colors cursor-pointer">🔍 Filtrar</button>
            @if(request()->has('barbero_id') || request()->has('servicio_id'))
                <a href="{{ route('ventas.index') }}" class="px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm p-2 rounded-xl transition-colors text-center">❌</a>
            @endif
        </div>
    </form>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                
               <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="py-4 px-6 w-12 text-center">
                        <a href="{{ sort_table_link('id') }}" class="hover:text-slate-900 inline-flex items-center gap-1">
                            N° {!! sort_table_icon('id') !!}
                        </a>
                    </th>
                    
                    <th class="py-4 px-6">
                        <a href="{{ sort_table_link('fecha') }}" class="hover:text-slate-900 inline-flex items-center gap-1">
                            Fecha / Hora {!! sort_table_icon('fecha') !!}
                        </a>
                    </th>
                    
                    <th class="py-4 px-6">
                        <a href="{{ sort_table_link('empleado_id') }}" class="hover:text-slate-900 inline-flex items-center gap-1">
                            Barbero {!! sort_table_icon('empleado_id') !!}
                        </a>
                    </th>
                    
                    <th class="py-4 px-6">
                        <a href="{{ sort_table_link('servicio_id') }}" class="hover:text-slate-900 inline-flex items-center gap-1">
                            Servicio realizado {!! sort_table_icon('servicio_id') !!}
                        </a>
                    </th>
                    
                    <th class="py-4 px-6 text-right">
                        <a href="{{ sort_table_link('precio_cobrado') }}" class="hover:text-slate-900 inline-flex items-center gap-1 justify-end w-full">
                            Precio Cobrado {!! sort_table_icon('precio_cobrado') !!}
                        </a>
                    </th>
                    
                    <th class="py-4 px-6 text-center w-28">Acciones</th>
                </tr>
            </thead>

                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($ventas as $venta)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3.5 px-6 text-center font-bold text-slate-500 whitespace-nowrap bg-slate-50/50">#{{ $venta->id }}</td>
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                <span class="font-medium text-slate-900">{{ date('d/m/Y', strtotime($venta->fecha)) }}</span>
                                <span class="text-xs text-slate-400 block">{{ $venta->hora }}</span>
                            </td>
                            <td class="py-3.5 px-6 font-medium text-slate-900 whitespace-nowrap">👨‍🎨 {{ $venta->empleado->nombre ?? 'No asignado' }}</td>
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-800 text-xs font-semibold rounded-lg">{{ $venta->servicio->nombre_servicio ?? 'Servicio Eliminado' }}</span>
                            </td>
                            
                            <td class="py-3.5 px-6 text-right whitespace-nowrap">
                                <span class="font-bold text-slate-900 block">${{ number_format($venta->precio_cobrado, 2) }}</span>
                                @if(isset($venta->servicio) && $venta->servicio->precio > $venta->precio_cobrado)
                                    @php $descuento = $venta->servicio->precio - $venta->precio_cobrado; @endphp
                                    <span class="text-[11px] text-red-500 font-medium block mt-0.5">📉 -${{ number_format($descuento, 2) }} <span class="text-slate-400 font-normal">(Orig: ${{ number_format($venta->servicio->precio, 2) }})</span></span>
                                @else
                                    <span class="text-[11px] text-slate-400 block mt-0.5 font-normal">Precio regular</span>
                                @endif
                            </td>
                            
                            <td class="py-3.5 px-6 text-center whitespace-nowrap">
                                <div class="flex justify-center items-center gap-1.5">
                                    <button 
                                        @click="prepararEdicion({{ $venta->id }}, {{ $venta->empleado_id }}, {{ $venta->servicio_id }}, '{{ $venta->precio_cobrado }}', '{{ url('/ventas/actualizar') }}')"
                                        title="Editar registro" class="p-1.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-600 hover:text-slate-900 rounded-lg transition-colors cursor-pointer"
                                    >
                                        ✏️
                                    </button>
                                    
                                    <button 
                                        @click="prepararEliminacion({{ $venta->id }}, '{{ url('/ventas/eliminar') }}')"
                                        title="Eliminar registro" class="p-1.5 bg-red-50 hover:bg-red-100 border border-red-100 text-red-500 hover:text-red-700 rounded-lg transition-colors cursor-pointer"
                                    >
                                        🗑️
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-12 text-center text-slate-400">No hay registros</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($ventas->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex items-center justify-between text-xs text-slate-500">
                <div>Mostrando del <span class="font-bold text-slate-700">{{ $ventas->firstItem() }}</span> al <span class="font-bold text-slate-700">{{ $ventas->lastItem() }}</span> de <span class="font-bold text-slate-700">{{ $ventas->total() }}</span> registros</div>
                <div class="flex gap-2">
                    @if($ventas->onFirstPage()) <span class="px-3 py-2 bg-slate-200/60 text-slate-400 rounded-xl font-semibold cursor-not-allowed">◀️ Anterior</span>
                    @else <a href="{{ $ventas->previousPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-slate-50">◀️ Anterior</a> @endif
                    @if($ventas->hasMorePages()) <a href="{{ $ventas->nextPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-slate-50">Siguiente ▶️</a>
                    @else <span class="px-3 py-2 bg-slate-200/60 text-slate-400 rounded-xl font-semibold cursor-not-allowed">Siguiente ▶️</span> @endif
                </div>
            </div>
        @endif
    </div>

    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;">
        <div @click.away="openModal = false" x-show="openModal" class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">✂️ Registrar Nuevo Servicio</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Asigna los detalles del corte cobrado en caja.</p>
                </div>
                <button @click="openModal = false" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-200 hover:text-slate-700 font-bold cursor-pointer">✕</button>
            </div>
            <form action="{{ route('ventas.store') }}" method="POST" class="p-6 space-y-5" x-data="{ precioSeleccionado: '', actualizarPrecio(e) { const option = e.target.options[e.target.selectedIndex]; this.precioSeleccionado = option.getAttribute('data-precio') || ''; } }">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="empleado_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">👨‍🎨 Barbero que Atendió</label>
                        <select name="empleado_id" id="empleado_id" required class="w-full rounded-xl border border-slate-200 p-2.5 bg-white text-slate-800 text-sm focus:border-amber-500 outline-none">
                            <option value="" disabled selected>Selecciona un barbero...</option>
                            @foreach($barberos as $barbero) <option value="{{ $barbero->id }}">{{ $barbero->nombre }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="servicio_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">💈 Servicio Solicitado</label>
                        <select name="servicio_id" id="servicio_id" required @change="actualizarPrecio($event)" class="w-full rounded-xl border border-slate-200 p-2.5 bg-white text-slate-800 text-sm focus:border-amber-500 outline-none">
                            <option value="" disabled selected>Selecciona un servicio...</option>
                            @foreach($servicios as $servicio) <option value="{{ $servicio->id }}" data-precio="{{ $servicio->precio }}">{{ $servicio->nombre_servicio }} (${{ number_format($servicio->precio, 2) }})</option> @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 pt-2">
                    <div>
                        <label for="precio_cobrado" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">💰 Precio Cobrado Real ($)</label>
                        <input type="number" step="0.01" name="precio_cobrado" id="precio_cobrado" required placeholder="0.00" x-model="precioSeleccionado" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm font-bold text-slate-900 focus:border-amber-500 outline-none">
                    </div>
                </div>
                <div class="pt-5 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" @click="openModal = false" class="px-4 py-2.5 border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 text-sm cursor-pointer">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-black rounded-xl text-sm shadow-md cursor-pointer">💾 Guardar Registro</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="openEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;">
        <div @click.away="openEditModal = false" x-show="openEditModal" class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">✏️ Corregir Registro de Venta</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Modifica los datos del ticket seleccionado.</p>
                </div>
                <button @click="openEditModal = false" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-200 hover:text-slate-700 font-bold cursor-pointer">✕</button>
            </div>
            <form :action="editRoute" method="POST" class="p-6 space-y-5">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="edit_empleado_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">👨‍🎨 Barbero que Atendió</label>
                        <select name="empleado_id" id="edit_empleado_id" required x-model="editEmpleado" class="w-full rounded-xl border border-slate-200 p-2.5 bg-white text-slate-800 text-sm focus:border-amber-500 outline-none">
                            @foreach($barberos as $barbero) <option value="{{ $barbero->id }}">{{ $barbero->nombre }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="edit_servicio_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">💈 Servicio Solicitado</label>
                        <select name="servicio_id" id="edit_servicio_id" required x-model="editServicio" @change="const option = $event.target.options[$event.target.selectedIndex]; editPrecio = option.getAttribute('data-precio') || '0.00';" class="w-full rounded-xl border border-slate-200 p-2.5 bg-white text-slate-800 text-sm focus:border-amber-500 outline-none">
                            @foreach($servicios as $servicio) <option value="{{ $servicio->id }}" data-precio="{{ $servicio->precio }}">{{ $servicio->nombre_servicio }} (${{ number_format($servicio->precio, 2) }})</option> @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 pt-2">
                    <div>
                        <label for="edit_precio_cobrado" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">💰 Precio Cobrado Real ($)</label>
                        <input type="number" step="0.01" name="precio_cobrado" id="edit_precio_cobrado" required x-model="editPrecio" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm font-bold text-slate-900 focus:border-amber-500 outline-none">
                    </div>
                </div>
                <div class="pt-5 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" @click="openEditModal = false" class="px-4 py-2.5 border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 text-sm cursor-pointer">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-black rounded-xl text-sm shadow-md cursor-pointer">💾 Actualizar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="openDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;">
        <div @click.away="openDeleteModal = false" x-show="openDeleteModal" class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col">
            
            <div class="p-6 text-center space-y-4">
                <div class="w-12 h-12 rounded-full bg-red-50 border border-red-200 text-red-500 text-xl font-bold flex items-center justify-center mx-auto">
                    ⚠️
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">¿Eliminar este registro de venta?</h2>
                    <p class="text-sm text-slate-500 mt-1">Esta acción es permanente y restará el precio cobrado del total facturado en los reportes de caja. ¿Deseas continuar?</p>
                </div>
            </div>

            <form :action="deleteRoute" method="POST" class="px-6 pb-6 pt-2 flex items-center gap-3">
                @csrf
                <button type="button" @click="openDeleteModal = false" class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 text-sm transition-all cursor-pointer text-center">
                    No, Cancelar
                </button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-sm transition-all shadow-md shadow-red-600/10 cursor-pointer text-center">
                    Sí, Eliminar
                </button>
            </form>
        </div>
    </div>

</div>
@endsection