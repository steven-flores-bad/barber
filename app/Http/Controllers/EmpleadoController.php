<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    // Listado principal con filtros, ordenamiento dinámico y paginación
    public function index(Request $request)
    {
        // 1. Capturar el ordenamiento del Helper o usar los valores por defecto
        $sort = $request->get('sort', 'nombre'); // Ordena por nombre por defecto
        $direction = $request->get('direction', 'asc');

        // 2. Validar que las columnas existan en la base de datos (Evita inyecciones SQL)
        $columnasValidas = ['id', 'nombre', 'telefono', 'estado'];
        if (!in_array($sort, $columnasValidas)) {
            $sort = 'nombre';
        }

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        // 3. Inicializar la query con el ordenamiento dinámico
        $query = Empleado::orderBy($sort, $direction);

        // Filtro de búsqueda por nombre
        $query->when($request->filled('buscar'), function ($q) use ($request) {
            return $q->where('nombre', 'like', '%' . $request->buscar . '%');
        });

        // Paginamos de 10 en 10 manteniendo TODOS los parámetros (filtros y ordenamiento) en la URL
        $empleados = $query->paginate(10)->appends($request->all());

        return view('barberos.index', compact('empleados'));
    }

    // Guarda un nuevo barbero
    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'estado'   => 'required|boolean',
        ]);

        $empleado = new Empleado();
        $empleado->nombre = $request->nombre;
        $empleado->telefono = $request->telefono;
        $empleado->estado = $request->estado;
        $empleado->save();

        return redirect()->route('barberos.index')->with('success', '¡Barbero registrado con éxito!');
    }

    // Actualiza un barbero existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre'   => 'required|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'estado'   => 'required|boolean',
        ]);

        $empleado = Empleado::findOrFail($id);
        $empleado->nombre = $request->nombre;
        $empleado->telefono = $request->telefono;
        $empleado->estado = $request->estado;
        $empleado->save();

        return redirect()->route('barberos.index')->with('success', '¡Datos del barbero actualizados correctamente!');
    }

    // Elimina un barbero
    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);
        
        try {
            $empleado->delete();
            return redirect()->route('barberos.index')->with('success', '¡Barbero eliminado del sistema!');
        } catch (\Exception $e) {
            // Frena la eliminación si el barbero ya tiene cortes amarrados en la tabla ventas_servicios
            return redirect()->route('barberos.index')->with('error', 'No se puede eliminar este barbero porque tiene ventas registradas a su nombre. Te recomendamos cambiar su estado a Inactivo.');
        }
    }
}