<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $fecha = $request->get('fecha', date('Y-m-d'));

        // 1. Obtener listado detallado por barbero (CORREGIDO EL JOIN AQUÍ '.')
        $ventasPorBarbero = DB::table('ventas_servicios')
            ->join('empleados', 'ventas_servicios.empleado_id', '=', 'empleados.id')
            ->join('servicios', 'ventas_servicios.servicio_id', '=', 'servicios.id')
            ->whereDate('ventas_servicios.fecha', $fecha)
            ->select(
                'empleados.nombre as barbero',
                'servicios.nombre_servicio as servicio',
                'ventas_servicios.precio_cobrado',
                'ventas_servicios.hora'
            )
            ->orderBy('empleados.nombre')
            ->orderBy('ventas_servicios.hora')
            ->get()
            ->groupBy('barbero');

        // 2. Resumen estadístico (CORREGIDO EL JOIN AQUÍ '.')
        $resumenBarberos = DB::table('ventas_servicios')
            ->join('empleados', 'ventas_servicios.empleado_id', '=', 'empleados.id')
            ->whereDate('ventas_servicios.fecha', $fecha)
            ->select(
                'empleados.nombre as barbero',
                DB::raw('COUNT(ventas_servicios.id) as total_cortes'),
                DB::raw('SUM(ventas_servicios.precio_cobrado) as total_recaudado')
            )
            ->groupBy('empleados.id', 'empleados.nombre')
            ->get();

        $totalCortesDia = $resumenBarberos->sum('total_cortes');
        $totalDineroDia = $resumenBarberos->sum('total_recaudado');

        return view('reportes.index', compact(
            'ventasPorBarbero', 
            'resumenBarberos', 
            'totalCortesDia', 
            'totalDineroDia', 
            'fecha'
        ));
    }

    public function descargarPdf(Request $request)
    {
        $fecha = $request->get('fecha', date('Y-m-d'));

        // Replicamos las correcciones también para la descarga del PDF
        $resumenBarberos = DB::table('ventas_servicios')
            ->join('empleados', 'ventas_servicios.empleado_id', '=', 'empleados.id')
            ->whereDate('ventas_servicios.fecha', $fecha)
            ->select(
                'empleados.nombre as barbero',
                DB::raw('COUNT(ventas_servicios.id) as total_cortes'),
                DB::raw('SUM(ventas_servicios.precio_cobrado) as total_recaudado')
            )
            ->groupBy('empleados.id', 'empleados.nombre')
            ->get();

        $ventasPorBarbero = DB::table('ventas_servicios')
            ->join('empleados', 'ventas_servicios.empleado_id', '=', 'empleados.id')
            ->join('servicios', 'ventas_servicios.servicio_id', '=', 'servicios.id')
            ->whereDate('ventas_servicios.fecha', $fecha)
            ->select(
                'empleados.nombre as barbero', 
                'servicios.nombre_servicio as servicio', 
                'ventas_servicios.precio_cobrado', 
                'ventas_servicios.hora'
            )
            ->get()
            ->groupBy('barbero');

        $totalCortesDia = $resumenBarberos->sum('total_cortes');
        $totalDineroDia = $resumenBarberos->sum('total_recaudado');

        $pdf = Pdf::loadView('reportes.pdf', compact(
            'ventasPorBarbero', 
            'resumenBarberos', 
            'totalCortesDia', 
            'totalDineroDia', 
            'fecha'
        ));

        return $pdf->download("reporte_ventas_{$fecha}.pdf");
    }
}