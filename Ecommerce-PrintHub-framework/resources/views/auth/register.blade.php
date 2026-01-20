@extends('layouts.guest_custom')

@section('content')
<div class="card shadow border-0 rounded-4" style="max-width: 500px; width: 100%;">
    <div class="card-body p-4">
        
        <div class="text-center mb-4">
            <h4 class="fw-bold text-dark">Crear Cuenta</h4>
            <p class="text-muted small">Únete a la comunidad PrintHub</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="row g-2 mb-3">
                <div class="col-6">
                    <input type="text" name="name" class="form-control bg-light border-0" placeholder="Nombre" required>
                </div>
                <div class="col-6">
                    <input type="text" name="surname" class="form-control bg-light border-0" placeholder="Apellidos">
                </div>
            </div>

            <div class="mb-3">
                <input type="email" name="email" class="form-control bg-light border-0" placeholder="tucorreo@email.com" required>
            </div>
            
            <div class="mb-3">
                <input type="tel" name="phone" class="form-control bg-light border-0" placeholder="Teléfono (Opcional)">
            </div>

            <div class="row g-2 mb-4">
                <div class="col-6">
                    <input type="password" name="password" class="form-control bg-light border-0" placeholder="Contraseña" required>
                </div>
                <div class="col-6">
                    <input type="password" name="password_confirmation" class="form-control bg-light border-0" placeholder="Confirmar" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-3 shadow-sm">
                REGISTRARSE
            </button>

            <div class="text-center mt-3">
                <span class="small text-muted">¿Ya tienes cuenta?</span>
                <a href="{{ route('login') }}" class="small fw-bold text-primary text-decoration-none">Entrar</a>
            </div>
        </form>
    </div>
</div>
@endsection