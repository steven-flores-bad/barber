<?php

namespace App\Http\Controllers;

use App\Models\CajaChica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class CajaChicaController extends Controller
{
    /**
     * Muestra el historial completo de Cajas Chicas
     */
    public function index()
    {
        // Paginamos de 10 en 10 ordenadas por la fecha más reciente
        $cajas = CajaChica::orderBy('fecha', 'desc')->paginate(10);
        return view('cajachica.index', compact('cajas'));
    }

    /**
     * Procesa la actualización de un monto ya registrado
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'monto_inicial' => 'required|numeric|min:0'
        ]);

        $caja = CajaChica::findOrFail($id);
        $caja->update([
            'monto_inicial' => $request->monto_inicial
        ]);

        return redirect()->route('cajachica.index')->with('success', 'Monto de caja chica actualizado con éxito.');
    }

    /**
     * Elimina un registro del historial
     */
    public function destroy($id)
    {
        $caja = CajaChica::findOrFail($id);
        $caja->delete();

        return redirect()->route('cajachica.index')->with('success', 'Registro de caja chica eliminado correctamente.');
    }

    /**
     * Descarga el reporte original en PDF del historial completo
     */
    public function descargarPdf()
    {
        $cajas = CajaChica::orderBy('fecha', 'desc')->get();
        
        // Sumatorias gerenciales para el reporte
        $totalFondos = $cajas->sum('monto_inicial');
        $promedioFondo = $cajas->avg('monto_inicial') ?? 0;

        $pdf = Pdf::loadView('cajachica.pdf_historial', compact('cajas', 'totalFondos', 'promedioFondo'));
        return $pdf->download('historial_cajas_chicas_'.time().'.pdf');
    }
}