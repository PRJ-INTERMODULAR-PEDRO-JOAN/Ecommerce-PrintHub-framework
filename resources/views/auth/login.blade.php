@extends('layouts.guest_custom')

@section('content')
<div class="card shadow border-0 rounded-4" style="max-width: 360px; width: 100%;">
    <div class="card-body p-4">
        
        <div class="text-center mb-4">
            <img src="{{ asset('img/logoPrintHub.jpeg') }}" alt="Logo" class="rounded-circle mb-2" width="60" height="60">
            <h4 class="fw-bold text-dark mb-1">Bienvenido Pedro</h4>
            <p class="text-muted small">Inicia sesión para continuar</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3">
                <i class="bi bi-exclamation-circle me-1"></i> Credenciales incorrectas.
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <input type="email" name="email" class="form-control form-control-lg fs-6 bg-light border-0" 
                       placeholder="Correo electrónico" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="mb-3">
                <input type="password" name="password" class="form-control form-control-lg fs-6 bg-light border-0" 
                       placeholder="Contraseña" required>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label small text-muted" for="remember">Recordarme</label>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-decoration-none small text-primary">¿Ayuda?</a>
                @endif
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-3 shadow-sm">
                INICIAR SESIÓN
            </button>
        </form>

        <div class="text-center mt-4">
            <p class="small text-muted mb-0">¿Eres nuevo aquí?</p>
            <a href="{{ route('register') }}" class="fw-bold text-primary text-decoration-none">Crear una cuenta</a>
        </div>
    </div>
</div>
@endsection