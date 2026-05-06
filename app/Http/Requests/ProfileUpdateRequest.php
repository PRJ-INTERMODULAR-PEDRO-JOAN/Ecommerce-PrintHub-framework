<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        // 1. Guardamos el ID de forma segura (null si ejecuta Scribe)
        $userId = $this->user() ? $this->user()->id : null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['nullable', 'string', 'max:255'], // Nuevo
            'phone' => ['nullable', 'string', 'max:20'],    // Nuevo
            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email', 
                'max:255', 
                // 2. AQUI USAMOS LA VARIABLE SEGURA $userId
                Rule::unique(User::class)->ignore($userId) 
            ],
        ];
    }
}