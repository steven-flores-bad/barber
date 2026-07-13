<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    // Panel Principal de Reportes (Botones Grandes)
    public function index()
    {
        return view('reportes.dashboard');
    }

    // Reporte 1: Ventas Detalladas (Cada servicio cobrado individualmente)
    public function ventasDetalladas(Request $request)
    {
        $desde = $request->get('desde', date('Y-m-d'));
        $hasta = $request->get('hasta', date('Y-m-d'));

        $ventas = DB::table('ventas_servicios')
            ->join('servicios', 'ventas_servicios.servicio_id', '=', 'servicios.id')
            ->join('empleados', 'ventas_servicios.empleado_id', '=', 'empleados.id')
            ->select(
                'ventas_servicios.id as ticket_id',
                'ventas_servicios.fecha',
                'ventas_servicios.hora',
                'servicios.nombre_servicio',
                'empleados.nombre as barbero',
                'ventas_servicios.precio_cobrado'
            )
            ->whereBetween('ventas_servicios.fecha', [$desde, $hasta])
            ->orderBy('ventas_servicios.id', 'desc')
            ->get();

        return view('reportes.ventas_detalladas', compact('ventas', 'desde', 'hasta'));
    }

    // Reporte 2: Ganancias del Día (Cierre de Caja Diario)
public function cierreCaja(Request $request)
{
    $fecha = $request->get('fecha', date('Y-m-d'));

    $granTotal = DB::table('ventas_servicios')
        ->whereDate('fecha', $fecha)
        ->sum('precio_cobrado');

    $totalCortes = DB::table('ventas_servicios')
        ->whereDate('fecha', $fecha)
        ->count();

    return view('reportes.cierre_caja', compact('granTotal', 'totalCortes', 'fecha'));
}

/**
 * Reporte NUEVO: Genera el PDF Oficial de Arqueo y Cierre de Caja
 */
public function pdfCierreCaja(Request $request)
{
    $fecha = $request->get('fecha', date('Y-m-d'));

    // 1. Totales Generales
    $granTotal = DB::table('ventas_servicios')->whereDate('fecha', $fecha)->sum('precio_cobrado');
    $totalCortes = DB::table('ventas_servicios')->whereDate('fecha', $fecha)->count();

    // 2. Desglose de recaudación por cada barbero (Útil para pagar comisiones al cierre)
    $resumenBarberos = DB::table('ventas_servicios')
        ->join('empleados', 'ventas_servicios.empleado_id', '=', 'empleados.id')
        ->select('empleados.nombre as barbero', DB::raw('COUNT(ventas_servicios.id) as cortes'), DB::raw('SUM(precio_cobrado) as total'))
        ->whereDate('fecha', $fecha)
        ->groupBy('empleados.id', 'empleados.nombre')
        ->get();

    // 3. Desglose por Nombre de Servicio (Para ver qué se vendió hoy)
    $resumenServicios = DB::table('ventas_servicios')
        ->join('servicios', 'ventas_servicios.servicio_id', '=', 'servicios.id')
        ->select('servicios.nombre_servicio as servicio', DB::raw('COUNT(ventas_servicios.id) as cantidad'), DB::raw('SUM(precio_cobrado) as total'))
        ->whereDate('fecha', $fecha)
        ->groupBy('servicios.id', 'servicios.nombre_servicio')
        ->get();

    $pdf = Pdf::loadView('reportes.pdf_cierre_caja', compact('granTotal', 'totalCortes', 'fecha', 'resumenBarberos', 'resumenServicios'));
    
    return $pdf->download("cierre_caja_{$fecha}.pdf");
}


    // Reporte 3: Rendimiento por Barbero (Por Día, Semana y Mes)
    public function rendimientoBarberos(Request $request)
    {
        $filtro = $request->get('periodo', 'dia');
        $hoy = Carbon::now();

        $query = DB::table('ventas_servicios')
            ->join('empleados', 'ventas_servicios.empleado_id', '=', 'empleados.id')
            ->join('servicios', 'ventas_servicios.servicio_id', '=', 'servicios.id')
            ->select(
                'empleados.nombre as barbero',
                'servicios.nombre_servicio',
                'ventas_servicios.precio_cobrado',
                'ventas_servicios.fecha',
                'ventas_servicios.hora'
            );

        if ($filtro === 'semana') {
            $query->whereBetween('ventas_servicios.fecha', [$hoy->startOfWeek()->format('Y-m-d'), $hoy->endOfWeek()->format('Y-m-d')]);
        } elseif ($filtro === 'mes') {
            $query->whereMonth('ventas_servicios.fecha', $hoy->month)
                  ->whereYear('ventas_servicios.fecha', $hoy->year);
        } else {
            $query->whereDate('ventas_servicios.fecha', $hoy->format('Y-m-d'));
        }

        $transacciones = $query->orderBy('empleados.nombre')->get();
        $ventasPorBarbero = $transacciones->groupBy('barbero');

        $resumenBarberos = [];
        foreach ($ventasPorBarbero as $barbero => $items) {
            $resumenBarberos[] = [
                'barbero' => $barbero,
                'total_servicios' => $items->count(),
                'total_recaudado' => $items->sum('precio_cobrado')
            ];
        }

        return view('reportes.rendimiento_barberos', compact('ventasPorBarbero', 'resumenBarberos', 'filtro'));
    }

    // Reporte 4: Servicios Más Vendidos (Top Servicios)
    public function topServicios()
    {
        $topServicios = DB::table('ventas_servicios')
            ->join('servicios', 'ventas_servicios.servicio_id', '=', 'servicios.id')
            ->select(
                'servicios.nombre_servicio',
                DB::raw('COUNT(ventas_servicios.id) as cantidad_vendida'),
                DB::raw('SUM(ventas_servicios.precio_cobrado) as ingresos_totales')
            )
            ->groupBy('servicios.id', 'servicios.nombre_servicio')
            ->orderBy('cantidad_vendida', 'desc')
            ->take(10)
            ->get();

        return view('reportes.top_servicios', compact('topServicios'));
    }

    // Exportador e Impresor unificado a PDF de cada reporte
    public function descargarPdf(Request $request)
    {
        $tipo = $request->get('tipo');
        $data = [];
        $titulo = "Reporte Administrative";

        if ($tipo === 'ventas') {
            $desde = $request->get('desde', date('Y-m-d'));
            $hasta = $request->get('hasta', date('Y-m-d'));
            $titulo = "Reporte de Ventas Detalladas ({$desde} a {$hasta})";
            $data['items'] = DB::table('ventas_servicios')
                ->join('servicios', 'ventas_servicios.servicio_id', '=', 'servicios.id')
                ->join('empleados', 'ventas_servicios.empleado_id', '=', 'empleados.id')
                ->select('ventas_servicios.id as ticket_id', 'ventas_servicios.fecha', 'ventas_servicios.hora', 'servicios.nombre_servicio', 'empleados.nombre as barbero', 'ventas_servicios.precio_cobrado')
                ->whereBetween('ventas_servicios.fecha', [$desde, $hasta])
                ->get();
        } elseif ($tipo === 'top') {
            $titulo = "Ranking de Servicios Más Vendidos (Top)";
            $data['items'] = DB::table('ventas_servicios')
                ->join('servicios', 'ventas_servicios.servicio_id', '=', 'servicios.id')
                ->select('servicios.nombre_servicio', DB::raw('COUNT(ventas_servicios.id) as cantidad_vendida'), DB::raw('SUM(ventas_servicios.precio_cobrado) as ingresos_totales'))
                ->groupBy('servicios.id', 'servicios.nombre_servicio')
                ->orderBy('cantidad_vendida', 'desc')->get();
        }

        $pdf = Pdf::loadView('reportes.pdf_generic', compact('data', 'titulo', 'tipo'));
        return $pdf->download('reporte_santein_'.time().'.pdf');
    }

    /**
     * Reporte NUEVO: Genera el PDF exclusivo de ventas detalladas con totales abajo de la tabla
     */
    public function pdfVentasDetalladas(Request $request)
    {
        $desde = $request->get('desde', date('Y-m-d'));
        $hasta = $request->get('hasta', date('Y-m-d'));

        // Consultamos la base de datos exactamente con los nombres de tus tablas y joins
        $ventas = DB::table('ventas_servicios')
            ->join('servicios', 'ventas_servicios.servicio_id', '=', 'servicios.id')
            ->join('empleados', 'ventas_servicios.empleado_id', '=', 'empleados.id')
            ->select(
                'ventas_servicios.id as ticket_id',
                'ventas_servicios.fecha',
                'ventas_servicios.hora',
                'servicios.nombre_servicio',
                'empleados.nombre as barbero',
                'ventas_servicios.precio_cobrado'
            )
            ->whereBetween('ventas_servicios.fecha', [$desde, $hasta])
            ->orderBy('ventas_servicios.id', 'asc') // Orden ascendente para lectura de reporte correlativo
            ->get();

        // Cargamos tu plantilla 'pdf_ventas_detalladas.blade.php'
        $pdf = Pdf::loadView('reportes.pdf_ventas_detalladas', compact('ventas', 'desde', 'hasta'));

        // Descargamos el archivo final
        return $pdf->download("reporte_ventas_{$desde}_a_{$hasta}.pdf");
    }

    /**
     * Guarda o actualiza el monto inicial de la caja chica para la fecha actual
     */
    public function guardarCajaChica(Request $request)
    {
        $request->validate([
            'monto_inicial' => 'required|numeric|min:0'
        ]);

        $hoy = date('Y-m-d');

        // Insertar o actualizar si ya existe un registro para el día de hoy
        DB::table('cajas_chicas')->updateOrInsert(
            ['fecha' => $hoy],
            [
                'monto_inicial' => $request->input('monto_inicial'),
                'updated_at' => now(),
                'created_at' => now()
            ]
        );

        return redirect()->route('dashboard')->with('success', 'Caja chica establecida correctamente.');
    }

    /**
     * Elimina el monto inicial de la caja chica del día de hoy
     */
    public function eliminarCajaChica()
    {
        $hoy = date('Y-m-d');

        // Eliminamos el registro de la fecha actual
        DB::table('cajas_chicas')->where('fecha', $hoy)->delete();

        return redirect()->route('dashboard')->with('success', 'El monto de la caja chica se ha eliminado correctamente.');
    }
}