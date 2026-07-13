<!-- ESTRUCTURA DEL MODAL DE ADVERTENCIA ORIGINAL -->
<div id="modal-eliminar-caja" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <!-- Fondo oscuro con desenfoque (Blur) -->
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs"></div>

    <!-- Contenedor del Mensaje -->
    <div class="bg-white rounded-3xl p-6 max-w-sm w-full relative z-10 shadow-2xl border border-slate-100 transform transition-all scale-100 text-center space-y-4 animate-fade-in">
        
        <!-- Ícono de Alerta Vibrante -->
        <div class="mx-auto w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center text-3xl border-2 border-amber-200 animate-pulse">
            ⚠️
        </div>

        <!-- Textos informativos ordenados -->
        <div class="space-y-1">
            <h3 class="text-lg font-black text-slate-900">¿Deseas corregir el monto?</h3>
            <p class="text-sm text-slate-500 leading-relaxed">
                Esta acción reiniciará el fondo base del día a <span class="font-extrabold text-slate-900">$0.00</span> y recalculará la Caja Total de la barbería.
            </p>
        </div>

        <!-- Botones de Acción Estilo 2 Columnas -->
        <div class="grid grid-cols-2 gap-3 pt-2">
            <!-- Botón Cancelar / Cerrar -->
            <button type="button" onclick="document.getElementById('modal-eliminar-caja').classList.add('hidden')" class="py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors cursor-pointer border border-slate-200">
                No, cancelar
            </button>

            <!-- Formulario Real que ejecuta la eliminación -->
            <form action="{{ route('reportes.cajachica.eliminar') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl transition-all shadow-md hover:scale-105 cursor-pointer">
                    Sí, reiniciar
                </button>
            </form>
        </div>
    </div>
</div>