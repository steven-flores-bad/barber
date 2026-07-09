<?php

namespace App\Http\Controllers;

use App\Models\VentaServicio; 
use App\Models\Empleado; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Obtener la fecha de hoy
        $hoy = now()->toDateString();

        // 2. Caja del Día: Suma de todo lo cobrado hoy
        $cajaDia = VentaServicio::where('fecha', $hoy)->sum('precio_cobrado') ?? 0.00;

        // 3. Servicios Hoy: Total de cortes realizados hoy
        $totalServiciosHoy = VentaServicio::where('fecha', $hoy)->count();

        // 4. Personal Activo: Conteo de barberos disponibles
        $barberosActivos = Empleado::where('estado', 1)->count();

        // 5. Ticket Promedio: Evitamos la división por cero si aún no hay ventas
        $ticketPromedio = $totalServiciosHoy > 0 ? ($cajaDia / $totalServiciosHoy) : 0.00;

        // 6. Últimos Servicios en Silla: Trae las últimas 5 ventas (Colección simple, NO paginada)
        $ultimasVentas = VentaServicio::with(['empleado', 'servicio'])
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // 7. Rendimiento del Equipo (Ranking de Barberos hoy)
        $ventasPorBarbero = VentaServicio::where('fecha', $hoy)
            ->select('empleado_id', DB::raw('count(*) as total_cortes'))
            ->groupBy('empleado_id')
            ->with('empleado')
            ->get();

        // Encontrar el valor máximo de cortes para calcular el porcentaje de la barra de progreso
        $maxCortes = $ventasPorBarbero->max('total_cortes') ?? 1;
        $rankingBarberos = [];

        foreach ($ventasPorBarbero as $vb) {
            if ($vb->empleado) {
                $porcentaje = ($vb->total_cortes / $maxCortes) * 100;

                $rankingBarberos[] = [
                    'nombre' => $vb->empleado->nombre,
                    'total_cortes' => $vb->total_cortes,
                    'porcentaje' => $porcentaje
                ];
            }
        }

        // Ordenar de mayor a menor número de servicios
        usort($rankingBarberos, function ($a, $b) {
            return $b['total_cortes'] <=> $a['total_cortes'];
        });

        // 8. Enviar variables limpias a la vista
        return view('dashboard.dashboard', compact(
            'cajaDia', 
            'totalServiciosHoy', 
            'barberosActivos', 
            'ticketPromedio', 
            'ultimasVentas', 
            'rankingBarberos'
        ));
    }
}