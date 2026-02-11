<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // <--- Asegúrate que esto esté aquí
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // ESTO ES LO CRUCIAL: Permite que el puerto 5173 use la sesión del 80
        $middleware->statefulApi(); 
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();