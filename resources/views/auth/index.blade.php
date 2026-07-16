@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    
    @if (session('success'))
        <div class="mb-4 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl text-sm flex items-center gap-2">
            ✨ {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">👥 Gestión de Usuarios</h1>
            <p class="text-slate-500 text-sm mt-1">Controla las cuentas de acceso del personal de la barbería y sus privilegios.</p>
        </div>
        <div>
            <a href="{{ route('usuarios.create') }}" class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-2.5 px-4 rounded-xl transition-all text-sm shadow-lg shadow-amber-500/10">
                ➕ Registrar Nuevo Usuario
            </a>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-[11px] font-bold uppercase tracking-wider">
                        <th class="px-6 py-4">Usuario</th>
                        <th class="px-6 py-4">Correo Electrónico</th>
                        <th class="px-6 py-4">Rol</th>
                        <th class="px-6 py-4 text-center">Permisos de Operación</th>
                        <th class="px-6 py-4 text-center">Acciones</th> <!-- Dejado como encabezado simple -->
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @foreach($usuarios as $user)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-semibold text-slate-900">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 bg-slate-900 text-amber-500 rounded-lg flex items-center justify-center font-bold text-xs uppercase">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <span>{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-500">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4">
                            @if($user->role === 'admin')
                                <span class="px-2.5 py-0.5 bg-red-500/10 text-red-600 border border-red-500/20 text-xs font-semibold rounded-full">
                                    Administrador
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 bg-slate-100 text-slate-600 border border-slate-200 text-xs font-semibold rounded-full">
                                    Empleado / Barbero
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                @if($user->role === 'admin')
                                    <span class="text-xs text-slate-400 italic">Control Total</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[11px] font-medium {{ $user->can_create ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-50 text-slate-400 line-through' }}">Agregar</span>
                                    <span class="px-2 py-0.5 rounded text-[11px] font-medium {{ $user->can_edit ? 'bg-amber-50 text-amber-600' : 'bg-slate-50 text-slate-400 line-through' }}">Editar</span>
                                    <span class="px-2 py-0.5 rounded text-[11px] font-medium {{ $user->can_delete ? 'bg-red-50 text-red-600' : 'bg-slate-50 text-slate-400 line-through' }}">Eliminar</span>
                                @endif
                            </div>
                        </td>
                        
                        <!-- Las acciones ahora están correctamente dentro del tbody y del foreach -->
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('usuarios.edit', $user->id) }}" title="Editar Usuario" class="text-slate-400 hover:text-amber-500 transition-colors text-base">
                                    ✏️
                                </a>

                                @if($user->id !== Auth::id())
                                <form action="{{ route('usuarios.destroy', $user->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar permanentemente a este usuario? Esta acción no se puede deshacer.');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Eliminar Usuario" class="text-slate-400 hover:text-red-500 transition-colors text-base focus:outline-none">
                                        ❌
                                    </button>
                                </form>
                                @else
                                <span class="text-[11px] text-slate-300 italic">(Tú)</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection