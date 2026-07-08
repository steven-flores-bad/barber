<?php

namespace App\Http\Controllers;

use App\Models\VentaServicio;
use App\Models\Empleado;  // <-- IMPORTANTE: Faltaba esta línea
use App\Models\Servicio;  // <-- IMPORTANTE: Faltaba esta línea
use Illuminate\Http\Request;

class VentaController extends Controller
{
    // Pantalla principal del historial de ventas
public function index(Request $request)
{
    // 1. Capturamos los parámetros de ordenamiento o asignamos los valores por defecto
    $sort = $request->get('sort', 'fecha'); // Columna por defecto
    $direction = $request->get('direction', 'desc'); // Dirección por defecto

    // Lista de columnas permitidas para evitar inyecciones SQL en el ->orderBy
    $columnasPermitidas = ['id', 'fecha', 'precio_cobrado', 'empleado_id', 'servicio_id'];
    if (!in_array($sort, $columnasPermitidas)) {
        $sort = 'fecha';
    }

    // 2. Iniciamos la consulta base con sus relaciones
    $query = VentaServicio::with(['empleado', 'servicio']);

    // 3. Aplicamos los filtros existentes (Barberos y Servicios)
    $query->when($request->filled('barbero_id'), function ($q) use ($request) {
        return $q->where('empleado_id', $request->barbero_id);
    });

    $query->when($request->filled('servicio_id'), function ($q) use ($request) {
        return $q->where('servicio_id', $request->servicio_id);
    });

    // 4. NUEVO: Aplicamos el ordenamiento dinámico elegido
    if ($sort === 'fecha') {
        // Si es por fecha, ordenamos secundariamente por hora para que sea exacto
        $query->orderBy('fecha', $direction)->orderBy('hora', $direction);
    } else {
        $query->orderBy($sort, $direction);
    }

    // 5. Paginamos manteniendo TODOS los parámetros en la URL (filtros + ordenamiento)
    $ventas = $query->paginate(10)->appends($request->all());

    // 6. Calculamos las ganancias totales reflejando los filtros activos
    $totalGanancias = $query->sum('precio_cobrado');

    // Catálogos para los formularios y filtros
    $barberos = Empleado::where('estado', 1)->orderBy('nombre', 'asc')->get();
    $servicios = Servicio::orderBy('nombre_servicio', 'asc')->get();

    return view('ventas.index', compact('ventas', 'totalGanancias', 'barberos', 'servicios'));
}

    // Guarda el nuevo corte en la base de datos
   public function store(Request $request)
{
    // 1. Ahora solo validamos el barbero y el servicio (fecha y hora ya no vienen del formulario)
    $request->validate([
        'empleado_id'    => 'required|exists:empleados,id',
        'servicio_id'    => 'required|exists:servicios,id',
        'precio_cobrado' => 'required|numeric|min:0',
    ]);

    // 2. Creamos el registro capturando el tiempo automáticamente
    $nuevaVenta = new VentaServicio();
    $nuevaVenta->empleado_id = $request->empleado_id;
    $nuevaVenta->servicio_id = $request->servicio_id;
    $nuevaVenta->precio_cobrado = $request->precio_cobrado;
    
    // Captura automática de fecha y hora del servidor
    $nuevaVenta->fecha = now()->format('Y-m-d'); // Guarda Ej: 2026-07-08
    $nuevaVenta->hora = now()->format('H:i:s');  // Guarda Ej: 11:25:00
    
    $nuevaVenta->save(); 

    return redirect()->route('ventas.index')->with('success', '¡Corte registrado correctamente!');
}

// Actualiza un registro de venta existente en la base de datos
public function update(Request $request, $id)
{
    // 1. Validamos los datos entrantes
    $request->validate([
        'empleado_id'    => 'required|exists:empleados,id',
        'servicio_id'    => 'required|exists:servicios,id',
        'precio_cobrado' => 'required|numeric|min:0',
    ]);

    // 2. Buscamos el registro real en la base de datos
    $venta = VentaServicio::findOrFail($id);
    
    // 3. Asignamos los nuevos valores corregidos
    $venta->empleado_id = $request->empleado_id;
    $venta->servicio_id = $request->servicio_id;
    $venta->precio_cobrado = $request->precio_cobrado;
    $venta->save(); // Guarda los cambios en MySQL

    // 4. Redireccionamos con mensaje de éxito
    return redirect()->route('ventas.index')->with('success', '¡Registro de venta actualizado correctamente!');
}

// Elimina de forma definitiva un registro de venta
public function destroy($id)
{
    // 1. Buscamos el registro por su ID (si no existe, lanza un error 404)
    $venta = VentaServicio::findOrFail($id);
    
    // 2. Eliminamos el registro de la base de datos
    $venta->delete();

    // 3. Redireccionamos a la lista con un mensaje de éxito
    return redirect()->route('ventas.index')->with('success', '¡El registro de venta ha sido eliminado correctamente!');
}
}