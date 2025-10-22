<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Mostrar formulario de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Login
    public function login(Request $request)
    {
        // Validar las credenciales
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Intentar autenticación
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Previene ataques de sesión
            return redirect()->intended('/dashboard'); // Redirige al dashboard
        }

        // Si falla la autenticación
        return back()->withErrors([
            'email' => 'Las credenciales no son válidas.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // Mostrar formulario de registro
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Procesar registro
    public function register(Request $request)
    {
        // Limpiar los datos antes de validar
        $request->merge([
            'nombre' => trim($request->nombre),
            'email' => trim($request->email),
        ]);

        // Validaciones principales
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|min:3|max:50|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'email' => 'required|string|email|max:255|unique:users|regex:/^[^@]+@[^@]+\.[^@]+$/',
            'password' => [
    'required',
    'string',
    'min:8',
    'confirmed',
    'regex:/^(?!\s)(.*\S)?$/'
],

        ], [
            'nombre.required' => 'El nombre completo es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'nombre.max' => 'El nombre no puede exceder 50 caracteres.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.unique' => 'Este correo ya existe.',
            'email.regex' => 'El formato del correo electrónico no es válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.regex' => 'La contraseña no puede tener espacios al inicio o al final.',
        ]);

        // Validaciones adicionales personalizadas
        $validator->after(function ($validator) use ($request) {
            $nombre = $request->nombre;
            $email = $request->email;
            $password = $request->password;
            
            // Verificar que el nombre no esté vacío después de trim
            if (empty($nombre)) {
                $validator->errors()->add('nombre', 'El nombre completo es obligatorio.');
            }
            
            // Verificar que el email no esté vacío después de trim
            if (empty($email)) {
                $validator->errors()->add('email', 'El correo electrónico no puede estar vacío.');
            }
            
            // Verificar que la contraseña no sea igual al nombre
            if (!empty($password) && !empty($nombre) && strtolower($password) === strtolower($nombre)) {
                $validator->errors()->add('password', 'La contraseña no puede ser igual al nombre.');
            }
            
            // Verificar que la contraseña no sea igual al email
            if (!empty($password) && !empty($email) && strtolower($password) === strtolower($email)) {
                $validator->errors()->add('password', 'La contraseña no puede ser igual al correo electrónico.');
            }
        });

        // Si hay errores, retornar con los errores
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Crear usuario con rol de empleado
        $user = User::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => User::ROL_EMPLEADO,
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }
}