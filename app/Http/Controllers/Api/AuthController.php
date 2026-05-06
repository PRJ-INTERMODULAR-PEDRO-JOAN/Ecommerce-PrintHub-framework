<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

/**
 * @group Autenticación
 *
 * APIs para gestionar el registro, inicio y cierre de sesión de usuarios.
 */
class AuthController extends Controller
{
    /**
     * Iniciar sesión
     *
     * Autentica al usuario y devuelve un token de Sanctum (Bearer Token) para usar en el resto de la API.
     *
     * @unauthenticated
     * @bodyParam email string required El correo del usuario. Example: admin@printhub.com
     * @bodyParam password string required La contraseña del usuario. Example: password
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Las credenciales no coinciden.'
            ], 401);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'accessToken' => $token,
            'user' => $user
        ]);
    }

    /**
     * Registrar usuario
     *
     * Crea un nuevo usuario en la base de datos y devuelve su token.
     *
     * @unauthenticated
     * @bodyParam name string required El nombre. Example: Pedro
     * @bodyParam surname string Los apellidos. Example: García
     * @bodyParam phone string El teléfono. Example: 600123456
     * @bodyParam email string required El correo. Example: pedro@printhub.com
     * @bodyParam password string required Contraseña (mín 8 chars). Example: secreto123
     * @bodyParam password_confirmation string required Confirmación de la contraseña. Example: secreto123
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'user' => $user,
            'accessToken' => $token
        ], 201);
    }

    /**
     * Cerrar sesión
     *
     * Revoca el token actual del usuario y cierra la sesión web. 
     * @authenticated
     */
    public function logout(Request $request)
    {
        // 1. Revocar el token de la API (Sanctum)
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        // 2. Comprobar si hay una sesión web activa antes de intentar borrarla
        if ($request->hasSession()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    /**
     * Listar sesiones (tokens) activas
     *
     * Obtiene una lista de todos los tokens de acceso que este usuario tiene activos. 
     * Cada token representa un inicio de sesión en un dispositivo o navegador diferente.
     *
     * @authenticated
     */
    public function activeSessions(Request $request)
    {
        // FÍJATE EN ESTA LÍNEA: Añadimos "use ($request)"
        $sessions = $request->user()->tokens->map(function ($token) use ($request) {
            return [
                'id' => $token->id,
                'nombre_dispositivo' => $token->name,
                'ultima_vez_usado' => $token->last_used_at ? $token->last_used_at->diffForHumans() : 'Nunca usado',
                'creado_el' => $token->created_at->format('d/m/Y H:i'),
                'es_sesion_actual' => $token->id === $request->user()->currentAccessToken()->id,
            ];
        });

        return response()->json([
            'sesiones_activas' => $sessions
        ]);
    }
}