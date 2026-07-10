<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    // Listado principal con filtros y ordenamiento
    public function index(Request $request)
    {
        // Parámetros de ordenamiento (Por defecto Nombre del Servicio de la A-Z)
        $sort = $request->get('sort', 'nombre_servicio');
        $direction = $request->get('direction', 'asc');

        // Validamos columnas para evitar inyecciones SQL
        $columnasPermitidas = ['id', 'nombre_servicio', 'precio'];
        if (!in_array($sort, $columnasPermitidas)) {
            $sort = 'nombre_servicio';
        }

        $query = Servicio::query();

        // Filtro de búsqueda por nombre del servicio
        $query->when($request->filled('buscar'), function ($q) use ($request) {
            return $q->where('nombre_servicio', 'like', '%' . $request->buscar . '%');
        });

        // Paginamos de 10 en 10 arrastrando los parámetros en la URL
        $servicios = $query->orderBy($sort, $direction)
                           ->paginate(10)
                           ->appends($request->all());

        return view('servicios.index', compact('servicios'));
    }

    // Guarda un nuevo servicio
    public function store(Request $request)
    {
        $request->validate([
            'nombre_servicio' => 'required|string|max:100',
            'precio'          => 'required|numeric|min:0',
        ]);

        $servicio = new Servicio();
        $servicio->nombre_servicio = $request->nombre_servicio;
        $servicio->precio = $request->precio;
        $servicio->save();

        return redirect()->route('servicios.index')->with('success', '¡Servicio agregado al catálogo con éxito!');
    }

    // Actualiza un servicio existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_servicio' => 'required|string|max:100',
            'precio'          => 'required|numeric|min:0',
        ]);

        $servicio = Servicio::findOrFail($id);
        $servicio->nombre_servicio = $request->nombre_servicio;
        $servicio->precio = $request->precio;
        $servicio->save();

        return redirect()->route('servicios.index')->with('success', '¡Servicio actualizado correctamente!');
    }

    // Elimina un servicio de forma segura
    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);
        
        try {
            $servicio->delete();
            return redirect()->route('servicios.index')->with('success', '¡El servicio fue eliminado del catálogo!');
        } catch (\Exception $e) {
            // Captura el bloqueo por integridad referencial (ON DELETE RESTRICT)
            return redirect()->route('servicios.index')->with('error', 'No se puede eliminar este servicio porque existen tickets de venta asociados a él en el historial. Te sugerimos dejarlo intacto para no alterar tus finanzas.');
        }
    }
}