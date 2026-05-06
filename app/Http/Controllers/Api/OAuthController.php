<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class OAuthController extends Controller
{
    /**
     * Redirigeix a Google
     */
    public function redirectToGoogle()
    {
        // El mètode stateless() és OBLIGATORI per a APIs / Vue (SPA)
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Rep la resposta de Google, crea/busca l'usuari i retorna el token a Vue
     */
    public function handleGoogleCallback()
    {
        try {
            // Obtenir dades de l'usuari des de Google
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Buscar si l'usuari ja existeix pel google_id o pel correu
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if (!$user) {
                // Si no existeix, creem el nou usuari
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => null, // No tenim password per a usuaris de Google
                ]);
            } else {
                // Si ja existia el correu però no estava vinculat a Google, l'actualitzem
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }
            }

            // Generem el Token propi de Sanctum
            $token = $user->createToken('authToken')->plainTextToken;

            // Al ser una SPA, no podem retornar JSON perquè venim d'una redirecció de Google.
            // Hem de redirigir de tornada al FRONTEND (Vue) passant el token per la URL.
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:5174');
            return redirect()->away($frontendUrl . '/oauth/callback?token=' . $token);

        } catch (Exception $e) {
            // Si hi ha error, redirigim al login de Vue amb un flag d'error
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:5174');
            return redirect()->away($frontendUrl . '/login?error=oauth_failed');
        }
    }
}