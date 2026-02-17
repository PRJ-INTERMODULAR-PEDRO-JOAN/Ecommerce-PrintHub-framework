<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    // --- LOGIN (Solo Token, Sin Sesión Web) ---
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 1. Buscar el usuario por email
        $user = User::where('email', $request->email)->first();

        // 2. Comprobar manualmente la contraseña
        // Si el usuario no existe O la contraseña no coincide, devolvemos error.
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Las credenciales no coinciden.'
            ], 401);
        }

        // 3. Generar el Token (Sanctum)
        // Opcional: Borrar tokens anteriores para que solo haya 1 sesión por dispositivo
        // $user->tokens()->delete(); 
        
        $token = $user->createToken('authToken')->plainTextToken;

        // 4. Devolver el token y el usuario (sin cookie de sesión)
        return response()->json([
            'message' => 'Login exitoso',
            'accessToken' => $token,
            'user' => $user
        ]);
    }

    // --- REGISTER ---
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

    // --- LOGOUT ---
    public function logout(Request $request)
    {
        // Revocar solo el token actual (el que se usó para esta petición)
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    }
}