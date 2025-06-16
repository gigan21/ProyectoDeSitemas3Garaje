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
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

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