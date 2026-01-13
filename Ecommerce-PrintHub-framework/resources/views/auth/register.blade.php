@extends('layouts.legacy')

@section('title', 'Registre')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/registerStyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/formstyle.css') }}">
@endpush

@section('content')
<div class="register-container">
    <h2>Crear Compte</h2>

    @if ($errors->any())
        <div class="error-messages">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="register-form">
        @csrf

        <div class="form-group">
            <label for="name">Nom d'Usuari</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label for="email">Correu Electrònic</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="password">Contrasenya</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmar Contrasenya</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn-submit">Registrar-se</button>
    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/register.js') }}"></script>
@endpush