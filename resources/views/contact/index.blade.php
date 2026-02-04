@extends('layouts.legacy')

@section('title', 'Contacto')

@section('content')
<main class="contenido-principal" style="padding-top: 120px; min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                
                <div class="text-center mb-5">
                    <h1 class="fw-bold display-5">Contacta con Nosotros</h1>
                    <p class="lead text-muted">Estamos aquí para resolver tus dudas.</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                        <strong>✅ ¡Mensaje Enviado!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="name" class="form-label fw-bold text-secondary">Nombre Completo</label>
                                <input type="text" class="form-control form-control-lg bg-light border-0" id="name" name="name" required placeholder="Ej: Juan Pérez" value="{{ Auth::check() ? Auth::user()->name : '' }}">
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label fw-bold text-secondary">Correo Electrónico</label>
                                <input type="email" class="form-control form-control-lg bg-light border-0" id="email" name="email" required placeholder="ejemplo@correo.com" value="{{ Auth::check() ? Auth::user()->email : '' }}">
                            </div>

                            <div class="mb-4">
                                <label for="subject" class="form-label fw-bold text-secondary">Asunto</label>
                                <input type="text" class="form-control form-control-lg bg-light border-0" id="subject" name="subject" placeholder="Ej: Consulta sobre pedido">
                            </div>

                            <div class="mb-4">
                                <label for="message" class="form-label fw-bold text-secondary">Mensaje</label>
                                <textarea class="form-control form-control-lg bg-light border-0" id="message" name="message" rows="5" required placeholder="Escribe aquí tu consulta..."></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold py-3 shadow-sm transition-all hover-scale">
                                    🚀 Enviar Mensaje
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <br>
</main>

<style>
    .hover-scale:hover { transform: scale(1.02); }
    .form-control:focus { box-shadow: none; border: 2px solid #0d6efd; background: white; }
</style>
@endsection