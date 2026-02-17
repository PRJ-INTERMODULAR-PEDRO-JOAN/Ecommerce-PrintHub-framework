<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Si no está logueado O su rol no es 'admin', prohibir paso
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'ACCESO DENEGADO: Solo administradores.');
        }

        return $next($request);
    }
}