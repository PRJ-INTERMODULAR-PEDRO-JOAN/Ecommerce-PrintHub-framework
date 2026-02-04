@extends('layouts.legacy')

@section('title', 'Mensaje Enviado')

@section('content')
<main class="contenido-principal" style="padding-top: 150px; min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                <div class="card shadow-lg border-0 rounded-4 text-center p-5">
                    <div class="card-body">
                        {{-- Icono animado o grande --}}
                        <div style="font-size: 80px; margin-bottom: 20px;">✅</div>
                        
                        <h1 class="fw-bold text-success mb-3">¡Enviado Correctamente!</h1>
                        
                        <p class="lead text-muted mb-5">
                            Hemos recibido tu mensaje. Nuestro equipo se pondrá en contacto contigo lo antes posible.
                        </p>

                        <div class="d-grid gap-3">
                            <a href="{{ route('home') }}" class="btn btn-primary btn-lg rounded-pill fw-bold">
                                🏠 Volver al Inicio
                            </a>
                            <a href="{{ route('products.list') }}" class="btn btn-outline-secondary btn-lg rounded-pill fw-bold">
                                🛒 Seguir Comprando
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>
@endsection