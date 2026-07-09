@props(['coleccion'])

@if($coleccion->hasPages())
    <div class="px-6 py-4 border border-t-0 border-slate-200 bg-slate-50 rounded-b-2xl flex items-center justify-between text-xs text-slate-500">
        <div>
            Mostrando del <span class="font-bold text-slate-700">{{ $coleccion->firstItem() }}</span> 
            al <span class="font-bold text-slate-700">{{ $coleccion->lastItem() }}</span> 
            de <span class="font-bold text-slate-700">{{ $coleccion->total() }}</span> registros
        </div>
        <div class="flex gap-2">
            @if($coleccion->onFirstPage()) 
                <span class="px-3 py-2 bg-slate-200/60 text-slate-400 rounded-xl font-semibold cursor-not-allowed">◀️ Anterior</span>
            @else 
                <a href="{{ $coleccion->previousPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-slate-50">◀️ Anterior</a> 
            @endif

            @if($coleccion->hasMorePages()) 
                <a href="{{ $coleccion->nextPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-slate-50">Siguiente ▶️</a>
            @else 
                <span class="px-3 py-2 bg-slate-200/60 text-slate-400 rounded-xl font-semibold cursor-not-allowed">Siguiente ▶️</span> 
            @endif
        </div>
    </div>
@endif