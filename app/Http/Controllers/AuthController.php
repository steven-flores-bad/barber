<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Muestra la vista de Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Procesa el inicio de sesión
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Por favor, ingresa un formato de correo válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard')); 
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    // Muestra el listado de usuarios (Solo Admin)
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes autorización para acceder a esta sección.');
        }

        $usuarios = User::all(); 

        return view('auth.index', compact('usuarios'));
    }

    // Muestra la vista de Registro interno (Solo Admin)
    public function showRegister()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes autorización para acceder a esta sección.');
        }

        return view('auth.register');
    }

    // Procesa el registro de un nuevo usuario con roles y permisos (Solo Admin)
    public function register(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes autorización para realizar esta acción.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,empleado'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'role.required' => 'Debes asignar un rol al usuario.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'can_create' => $request->role === 'admin' ? true : $request->has('can_create'),
            'can_edit'   => $request->role === 'admin' ? true : $request->has('can_edit'),
            'can_delete' => $request->role === 'admin' ? true : $request->has('can_delete'),
        ]);

        return redirect()->route('usuarios.index')->with('success', '¡Nuevo usuario registrado con éxito!');
    }

    // Muestra el formulario de edición con los datos del usuario
    public function edit($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes autorización.');
        }

        $usuario = User::findOrFail($id);
        return view('auth.edit', compact('usuario'));
    }

    // Procesa los cambios del usuario
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes autorización.');
        }

        $usuario = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
            'role' => ['required', 'in:admin,empleado'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'], // Contraseña opcional al editar
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'can_create' => $request->role === 'admin' ? true : $request->has('can_create'),
            'can_edit'   => $request->role === 'admin' ? true : $request->has('can_edit'),
            'can_delete' => $request->role === 'admin' ? true : $request->has('can_delete'),
        ];

        // Solo actualiza la contraseña si el admin escribió una nueva
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        return redirect()->route('usuarios.index')->with('success', '¡Usuario actualizado con éxito!');
    }

    // Elimina al usuario del sistema
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes autorización.');
        }

        $usuario = User::findOrFail($id);

        // Evitar que el administrador se elimine a sí mismo por error
        if ($usuario->id === Auth::id()) {
            return back()->withErrors(['error' => 'No puedes eliminar tu propia cuenta de administrador de la sesión activa.']);
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }

    // Cierra la sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}