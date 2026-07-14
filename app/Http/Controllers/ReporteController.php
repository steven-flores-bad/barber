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

        $cajaChicaReg = DB::table('cajas_chicas')
            ->where('fecha', $fecha)
            ->first();

        $cajaChicaHoy = $cajaChicaReg ? $cajaChicaReg->monto_inicial : 0.00;

        $granTotal = DB::table('ventas_servicios')->whereDate('fecha', $fecha)->sum('precio_cobrado') ?? 0.00;
        $totalCortes = DB::table('ventas_servicios')->whereDate('fecha', $fecha)->count();

        $cajaTotalAcumulada = $granTotal + $cajaChicaHoy;

        $resumenBarberos = DB::table('ventas_servicios')
            ->join('empleados', 'ventas_servicios.empleado_id', '=', 'empleados.id')
            ->select('empleados.nombre as barbero', DB::raw('COUNT(ventas_servicios.id) as cortes'), DB::raw('SUM(precio_cobrado) as total'))
            ->whereDate('fecha', $fecha)
            ->groupBy('empleados.id', 'empleados.nombre')
            ->get();

        $resumenServicios = DB::table('ventas_servicios')
            ->join('servicios', 'ventas_servicios.servicio_id', '=', 'servicios.id')
            ->select('servicios.nombre_servicio as servicio', DB::raw('COUNT(ventas_servicios.id) as cantidad'), DB::raw('SUM(precio_cobrado) as total'))
            ->whereDate('fecha', $fecha)
            ->groupBy('servicios.id', 'servicios.nombre_servicio')
            ->get();

        $pdf = Pdf::loadView('reportes.pdf_cierre_caja', compact(
            'granTotal', 
            'totalCortes', 
            'fecha', 
            'cajaChicaHoy', 
            'cajaTotalAcumulada', 
            'resumenBarberos', 
            'resumenServicios'
        ));
        
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
        $titulo = "Reporte Administrativo";

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

            $pdf = Pdf::loadView('reportes.pdf_generic', compact('data', 'titulo', 'tipo'));
            return $pdf->download('reporte_ventas_'.time().'.pdf');

        } elseif ($tipo === 'top') {
            $titulo = "Ranking de Servicios Más Vendidos (Top)";
            $data['items'] = DB::table('ventas_servicios')
                ->join('servicios', 'ventas_servicios.servicio_id', '=', 'servicios.id')
                ->select('servicios.nombre_servicio', DB::raw('COUNT(ventas_servicios.id) as cantidad_vendida'), DB::raw('SUM(ventas_servicios.precio_cobrado) as ingresos_totales'))
                ->groupBy('servicios.id', 'servicios.nombre_servicio')
                ->orderBy('cantidad_vendida', 'desc')->get();

            $pdf = Pdf::loadView('reportes.pdf_generic', compact('data', 'titulo', 'tipo'));
            return $pdf->download('reporte_top_'.time().'.pdf');

        }elseif ($tipo === 'barberos') {
            $filtro = $request->get('periodo', 'dia');
            $hoy = Carbon::now();
            $titulo = "Rendimiento de Equipo - Filtro: " . strtoupper($filtro);

            $query = DB::table('ventas_servicios')
                ->join('empleados', 'ventas_servicios.empleado_id', '=', 'empleados.id')
                ->join('servicios', 'ventas_servicios.servicio_id', '=', 'servicios.id')
                ->select(
                    'empleados.nombre as barbero', 
                    'servicios.nombre_servicio', 
                    'ventas_servicios.precio_cobrado'
                );

            if ($filtro === 'semana') {
                $inicio = (clone $hoy)->startOfWeek()->format('Y-m-d');
                $fin = (clone $hoy)->endOfWeek()->format('Y-m-d');
                $query->whereBetween('ventas_servicios.fecha', [$inicio, $fin]);
            } elseif ($filtro === 'mes') {
                $query->whereMonth('ventas_servicios.fecha', $hoy->month)->whereYear('ventas_servicios.fecha', $hoy->year);
            } else {
                $query->whereDate('ventas_servicios.fecha', $hoy->format('Y-m-d'));
            }

            $transacciones = $query->get();

            // Agrupamos por Barbero
            $ventasPorBarbero = $transacciones->groupBy('barbero')->map(function ($serviciosDelBarbero) {
                // Agrupamos por tipo de servicio
                return $serviciosDelBarbero->groupBy('nombre_servicio')->map(function ($grupoServicio) {
                    $cantidad = $grupoServicio->count();
                    $totalCobrado = $grupoServicio->sum('precio_cobrado');
                    
                    return [
                        'cantidad' => $cantidad,
                        'precio_unitario' => $cantidad > 0 ? ($totalCobrado / $cantidad) : 0, // Precio cobrado individual
                        'total_cobrado' => $totalCobrado
                    ];
                });
            });

            $pdf = Pdf::loadView('reportes.pdfs.pdf_barberos', compact('ventasPorBarbero', 'titulo', 'filtro'));
            return $pdf->download('reporte_rendimiento_barberos_'.time().'.pdf');
        }
    } 

    /**
     * Reporte NUEVO: Genera el PDF exclusivo de ventas detalladas con totales abajo de la tabla
     */
    public function pdfVentasDetalladas(Request $request)
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
            ->orderBy('ventas_servicios.id', 'asc')
            ->get();

        $pdf = Pdf::loadView('reportes.pdf_ventas_detalladas', compact('ventas', 'desde', 'hasta'));
        return $pdf->download("reporte_ventas_{$desde}_a_{$hasta}.pdf");
    }
}