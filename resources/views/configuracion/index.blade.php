@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">⚙️ Configuración del Sistema</h1>
        <p class="text-slate-500 text-sm mt-1">Administra los parámetros generales, seguridad y accesos de la barbería.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        @if(Auth::user()->role === 'admin')
        <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl overflow-hidden hover:border-amber-500/50 transition-all group flex flex-col justify-between">
            <div class="h-1 w-full" style="background: repeating-linear-gradient(45deg, #cc292b, #cc292b 10px, #ffffff 10px, #ffffff 20px, #2b5c8f 20px, #2b5c8f 30px, #ffffff 30px, #ffffff 40px);"></div>
            
            <div class="p-6 flex-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-amber-500/10 text-amber-500 rounded-xl text-2xl group-hover:bg-amber-500 group-hover:text-slate-950 transition-colors">
                        👥
                    </div>
                    <span class="px-2.5 py-0.5 bg-red-500/10 text-red-400 text-[10px] font-bold uppercase tracking-wider rounded-full border border-red-500/20">
                        Solo Admin
                    </span>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Control de Usuarios y Roles</h3>
                <p class="text-slate-400 text-xs leading-relaxed">
                    Registra nuevas cuentas para tu personal (barberos, cajeros) y configura con precisión qué pueden hacer: **ver, agregar, editar o eliminar** información.
                </p>
            </div>
            
            <div class="p-6 pt-0">
                <a href="{{ route('usuarios.index') }}" class="w-full inline-flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-2.5 px-4 rounded-xl transition-colors text-xs shadow-md">
                    Gestionar Usuarios ➔
                </a>
            </div>
        </div>
        @endif



    </div>
</div>
@endsection