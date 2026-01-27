@extends('layouts.guest_custom')

@section('content')
<main class="login-container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="login-card shadow-lg p-4" style="max-width: 500px;">
        <div class="text-center mb-4">
            <img src="{{ asset('img/logoPrintHub.jpeg') }}" width="60" class="rounded-circle mb-3">
            <h4>Verifica tu correo</h4>
        </div>

        <div class="mb-4 text-muted small text-center">
            {{ __('Gracias por registrarte. Antes de empezar, ¿podrías verificar tu dirección de correo electrónico haciendo clic en el enlace que te acabamos de enviar? Si no recibiste el correo, con gusto te enviaremos otro.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success small mb-4">
                {{ __('Se ha enviado un nuevo enlace de verificación a la dirección de correo que proporcionaste durante el registro.') }}
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mt-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm">
                    {{ __('Reenviar correo') }}
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link text-muted btn-sm text-decoration-none">
                    {{ __('Cerrar Sesión') }}
                </button>
            </form>
        </div>
    </div>
</main>
@endsection