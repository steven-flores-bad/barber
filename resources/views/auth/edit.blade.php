@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl overflow-hidden mt-6">
    <div class="h-1.5 w-full" style="background: repeating-linear-gradient(45deg, #cc292b, #cc292b 10px, #ffffff 10px, #ffffff 20px, #2b5c8f 20px, #2b5c8f 30px, #ffffff 30px, #ffffff 40px);"></div>

    <div class="p-8" x-data="{ userRole: '{{ $usuario->role }}' }">
        <div class="text-center mb-6">
            <span class="text-4xl">📝</span>
            <h2 class="text-2xl font-bold text-white mt-3">Modificar <span class="text-amber-500">Usuario</span></h2>
            <p class="text-slate-400 text-xs mt-1">Actualiza los datos de acceso o privilegios de **{{ $usuario->name }}**</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nombre Completo</label>
                    <input type="text" name="name" value="{{ old('name', $usuario->name) }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-amber-500 transition-colors" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Correo Electrónico</label>
                    <input type="email" name="email" value="{{ old('email', $usuario->email) }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-amber-500 transition-colors" required>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Rol del Usuario</label>
                <select name="role" x-model="userRole" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-amber-500 transition-colors cursor-pointer">
                    <option value="empleado">Empleado / Barbero (Permisos Limitados)</option>
                    <option value="admin">Administrador General (Control Total)</option>
                </select>
            </div>

            <div x-show="userRole === 'empleado'" x-transition class="p-4 bg-slate-950 border border-slate-800 rounded-xl space-y-3">
                <p class="text-xs font-bold text-amber-500 uppercase tracking-wider mb-1">Permisos de Operación</p>
                
                <label class="flex items-center gap-3 text-sm text-slate-300 cursor-pointer hover:text-white">
                    <input type="checkbox" name="can_create" value="1" {{ $usuario->can_create ? 'checked' : '' }} class="rounded border-slate-800 text-amber-500 focus:ring-amber-500 bg-slate-900 h-4 w-4">
                    <span>Permitir <strong>Agregar</strong> (Crear registros en el sistema)</span>
                </label>

                <label class="flex items-center gap-3 text-sm text-slate-300 cursor-pointer hover:text-white">
                    <input type="checkbox" name="can_edit" value="1" {{ $usuario->can_edit ? 'checked' : '' }} class="rounded border-slate-800 text-amber-500 focus:ring-amber-500 bg-slate-900 h-4 w-4">
                    <span>Permitir <strong>Editar</strong> (Modificar registros existentes)</span>
                </label>

                <label class="flex items-center gap-3 text-sm text-slate-300 cursor-pointer hover:text-white">
                    <input type="checkbox" name="can_delete" value="1" {{ $usuario->can_delete ? 'checked' : '' }} class="rounded border-slate-800 text-amber-500 focus:ring-amber-500 bg-slate-900 h-4 w-4">
                    <span class="text-red-400">Permitir <strong>Eliminar</strong> (Borrar registros de las tablas)</span>
                </label>
            </div>

            <div class="border-t border-slate-800/60 pt-4">
                <p class="text-slate-400 text-[11px] mb-3 bg-amber-500/5 p-2 rounded-lg border border-amber-500/10 text-amber-400/90">⚠️ Deja los siguientes campos vacíos si **no** deseas cambiar la contraseña actual del usuario.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nueva Contraseña</label>
                        <input type="password" name="password" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-amber-500 transition-colors" placeholder="Opcional">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-amber-500 transition-colors" placeholder="Opcional">
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('usuarios.index') }}" class="w-1/3 bg-slate-800 hover:bg-slate-700 text-slate-300 text-center font-bold py-3 px-4 rounded-xl transition-colors text-sm">
                    Cancelar
                </a>
                <button type="submit" class="w-2/3 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-3 px-4 rounded-xl transition-colors text-sm shadow-lg">
                    💾 Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection