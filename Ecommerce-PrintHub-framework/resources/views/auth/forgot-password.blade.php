@extends('layouts.guest_custom')

@section('content')
<main class="login-container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="login-card shadow-lg p-4" style="max-width: 450px;">
        <div class="text-center mb-4">
            <img src="{{ asset('img/logoPrintHub.jpeg') }}" width="60" class="rounded-circle mb-3">
            <h4>Recuperar Contraseña</h4>
        </div>

        <div class="mb-4 text-muted small">
            {{ __('¿Olvidaste tu contraseña? No hay problema. Dinos tu correo electrónico y te enviaremos un enlace para restablecerla.') }}
        </div>

        @if (session('status'))
            <div class="alert alert-success small mb-3">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label small fw-bold">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">
                    {{ __('Enviar enlace') }}
                </button>
            </div>
            
            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-decoration-none small text-muted">Volver al Login</a>
            </div>
        </form>
    </div>
</main>
@endsection