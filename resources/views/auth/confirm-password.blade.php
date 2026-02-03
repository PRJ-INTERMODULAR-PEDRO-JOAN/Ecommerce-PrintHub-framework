@extends('layouts.guest_custom')

@section('content')
<main class="login-container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="login-card shadow-lg p-4" style="max-width: 450px;">
        <div class="text-center mb-4">
            <h4>Zona Segura</h4>
            <p class="small text-muted">{{ __('Por favor, confirma tu contraseña antes de continuar.') }}</p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="mb-3">
                <label for="password" class="form-label small fw-bold">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password">
                @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">
                    {{ __('Confirmar') }}
                </button>
            </div>
        </form>
    </div>
</main>
@endsection