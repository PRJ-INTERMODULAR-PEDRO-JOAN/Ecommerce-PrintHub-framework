@extends('layouts.legacy')

@section('title', 'Mi Cuenta')

@section('content')
<div class="container py-5" style="margin-top: 80px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Panel de Usuario</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        ¡Has iniciado sesión correctamente!
                    </div>

                    <h4 class="mb-4">Mis Datos</h4>
                    
                    <ul class="list-group mb-4">
                        <li class="list-group-item"><strong>Nombre:</strong> {{ Auth::user()->name }}</li>
                        <li class="list-group-item"><strong>Apellidos:</strong> {{ Auth::user()->surname ?? 'No especificado' }}</li>
                        <li class="list-group-item"><strong>Email:</strong> {{ Auth::user()->email }}</li>
                        <li class="list-group-item"><strong>Teléfono:</strong> {{ Auth::user()->phone ?? 'No especificado' }}</li>
                        <li class="list-group-item"><strong>Rol:</strong> 
                            @if(Auth::user()->role === 'admin')
                                <span class="badge bg-danger">Administrador</span>
                            @else
                                <span class="badge bg-info">Cliente</span>
                            @endif
                        </li>
                    </ul>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('profile.edit') }}" class="btn btn-warning">
                            ✏️ Editar Perfil
                        </a>

                        {{-- SECCIÓN SOLO PARA ADMINISTRADORES --}}
                        @if(Auth::user()->role === 'admin')
                            
                            {{-- Botón para descargar el CSV del Chatbot --}}
                            <a href="{{ route('admin.export') }}" class="btn btn-success">
                                🤖 Exportar Productos (Chatbot)
                            </a>

                            {{-- Botón para importar productos --}}
                            <a href="{{ route('admin.import') }}" class="btn btn-dark">
                                📥 Importar Productos (Excel)
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection