<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    // Muestra el formulario
    public function index()
    {
        return view('contact.index');
    }

    // Guarda el mensaje y REDIRIGE A LA PÁGINA DE ÉXITO
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        Contact::create($request->all());

        // CAMBIO: Ahora redirigimos a la ruta 'contact.success'
        return redirect()->route('contact.success');
    }

    // Muestra la nueva página de confirmación
    public function success()
    {
        return view('contact.success');
    }
}