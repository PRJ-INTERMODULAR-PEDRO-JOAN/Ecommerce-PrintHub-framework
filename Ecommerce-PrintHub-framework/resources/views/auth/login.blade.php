@extends('layouts.login')

@section('content')
<main class="login-container">
    <div class="login-card">
        <div class="login-header">
            <a href="{{ route('login') }}">
                <img src="{{ asset('img/logoPrintHub.jpeg') }}" alt="PrintHub Logo" class="login-logo">
            </a>
            <h1>Iniciar Sesión</h1>
            <p>Bienvenido de nuevo a PrintHub</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                Usuario o contraseña incorrectos.
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="login-form">
            @csrf <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <div class="input-container">
                    <input type="email" id="email" name="email" 
                           value="{{ old('email') }}" required autofocus 
                           placeholder="admin@printhub.com">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="input-container">
                    <input type="password" id="password" name="password" 
                           required placeholder="••••••••">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-login">Entrar</button>
            </div>

            <div class="login-footer">
                <p>¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate aquí</a></p>
            </div>
        </form>
    </div>
</main>
@endsection