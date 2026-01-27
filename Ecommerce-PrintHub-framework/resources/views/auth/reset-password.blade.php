@extends('layouts.guest_custom')

@section('content')
<main class="login-container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="login-card shadow-lg p-4" style="max-width: 450px;">
        <div class="text-center mb-4">
            <h4>Nueva Contraseña</h4>
        </div>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="mb-3">
                <label for="email" class="form-label small fw-bold">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $request->email) }}" required autofocus>
                @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label small fw-bold">Nueva Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" required>
                @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label small fw-bold">Confirmar Contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">
                    {{ __('Restablecer Contraseña') }}
                </button>
            </div>
        </form>
    </div>
</main>
@endsection