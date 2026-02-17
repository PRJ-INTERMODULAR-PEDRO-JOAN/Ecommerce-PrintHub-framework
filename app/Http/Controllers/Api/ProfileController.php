<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Actualizar información del perfil (Nombre, Email, Teléfono).
     */
    public function update(ProfileUpdateRequest $request)
    {
        // Validar datos usando el Request por defecto de Laravel Breeze
        $request->user()->fill($request->validated());

        // Si el email cambió, invalidar la verificación anterior
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return response()->json([
            'message' => 'Perfil actualizado correctamente',
            'user' => $request->user(),
        ]);
    }

    /**
     * Actualizar la contraseña.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'Contraseña actualizada correctamente',
        ]);
    }

    /**
     * Eliminar la cuenta del usuario.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Borrar todos los tokens de API antes de borrar el usuario
        $user->tokens()->delete();

        $user->delete();

        return response()->json([
            'message' => 'Cuenta eliminada correctamente',
        ]);
    }
}