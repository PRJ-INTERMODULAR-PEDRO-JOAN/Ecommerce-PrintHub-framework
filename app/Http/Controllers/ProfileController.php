<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * @group Perfil de Usuario
 *
 * APIs para gestionar y actualizar los datos del usuario autenticado.
 */
class ProfileController extends Controller
{
    /**
     * Actualizar información del perfil
     *
     * Modifica los datos básicos del usuario (Nombre, Apellidos, Teléfono, Email).
     *
     * @authenticated
     * @bodyParam name string required El nombre del usuario. Example: Pedro
     * @bodyParam surname string Los apellidos. Example: García
     * @bodyParam phone string El teléfono. Example: 600123456
     * @bodyParam email string required El correo electrónico. Example: pedro@printhub.com
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
     * Actualizar la contraseña
     *
     * Cambia la contraseña del usuario logueado. Es necesario proporcionar la contraseña actual correcta.
     *
     * @authenticated
     * @bodyParam current_password string required La contraseña actual. Example: passwordVieja123
     * @bodyParam password string required La nueva contraseña (mínimo 8 caracteres). Example: passwordNueva123
     * @bodyParam password_confirmation string required Confirmación de la nueva contraseña. Example: passwordNueva123
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
     * Eliminar la cuenta del usuario
     *
     * Borra de forma permanente la cuenta del usuario actual y destruye todos sus tokens de acceso.
     *
     * @authenticated
     * @bodyParam password string required La contraseña actual para confirmar el borrado. Example: miPassword123
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