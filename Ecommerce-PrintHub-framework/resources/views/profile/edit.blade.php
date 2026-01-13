@extends('layouts.legacy')

@section('title', 'El meu Perfil')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/perfilStyle.css') }}">
@endpush

@section('content')
<div class="profile-container">
    <h2>El meu Perfil</h2>
    
    <div class="profile-card">
        <h3>Informació de l'usuari</h3>
        <p><strong>Nom:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        
        <h3>Actualitzar Perfil</h3>
        <form method="post" action="{{ route('profile.update') }}" class="profile-form">
            @csrf
            @method('patch')
            
            <label for="name">Nom</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
            
            <label for="email">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
            
            <button type="submit">Guardar Canvis</button>
        </form>
    </div>
</div>
@endsection