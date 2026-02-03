@extends('layouts.legacy')

@section('title', 'Importar Productos')

@section('content')
<div class="container py-5" style="margin-top: 100px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-dark text-white text-center">
                    <h3 class="mb-0">📥 Importar Excel de Productos</h3>
                </div>
                <div class="card-body p-5">
                    
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="mb-4">
                        <p class="text-muted">Sube un archivo <strong>.xlsx</strong> o <strong>.csv</strong> con estas columnas en la primera fila:</p>
                        <div class="bg-light p-2 border rounded text-center">
                            <code>sku, name, description, price, stock, category, image</code>
                        </div>
                    </div>

                    <form action="{{ route('admin.import.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <input class="form-control form-control-lg" type="file" id="file" name="file" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Subir e Importar</button>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Volver</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection