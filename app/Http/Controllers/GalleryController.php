<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        // Asegúrate de que estos archivos existan REALMENTE en public/img/
        $secciones = [
            [
                'id' => 'puentes',
                'titulo' => 'PUENTES',
                'imagenes' => ['puente1.jpg', 'puente2.jpg', 'puente3.jpg', 'puente4.jpg']
            ],
            [
                'id' => 'vehiculos',
                'titulo' => 'VEHÍCULOS',
                'imagenes' => ['coche1.jpg', 'coche2.jpg', 'coche3.jpg', 'coche4.jpg']
            ],
            [
                'id' => 'videojuegos',
                'titulo' => 'VIDEOJUEGOS',
                'imagenes' => ['juego1.jpg', 'juego2.jpg', 'juego3.jpg', 'juego4.jpg']
            ]
        ];

        return view('gallery.index', compact('secciones'));
    }
}