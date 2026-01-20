@extends('layouts.guest_custom')

@section('content')
<main class="register-container">
    <div class="register-card">
        
        <div class="register-header text-center mb-4">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('img/logoPrintHub.jpeg') }}" alt="Logo" class="register-logo rounded-circle mb-3" style="width: 70px;">
            </a>
            <h2>Crear Compte</h2>
            <p class="text-muted">Uneix-te a la comunitat maker</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger p-2 small">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="register-form">
            @csrf

            <div class="row g-2">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nom</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="surname" class="form-label">Cognoms</label>
                    <input type="text" id="surname" name="surname" class="form-control" value="{{ old('surname') }}">
                </div>
            </div>

            <div class="row g-2">
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Telèfon</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contrasenya</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label">Confirmar Contrasenya</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2">Registrar-se</button>

            <div class="text-center mt-3">
                <p>Ja tens compte? <a href="{{ route('login') }}">Inicia sessió</a></p>
            </div>
        </form>
    </div>
</main>
@endsection