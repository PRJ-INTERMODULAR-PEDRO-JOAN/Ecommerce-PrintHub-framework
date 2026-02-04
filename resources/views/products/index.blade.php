@extends('layouts.legacy')

@section('title', 'Catálogo Completo')

@section('content')
<main class="contenido-principal" style="padding-top: 120px; min-height: 100vh;">
    
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold">Nuestro Catálogo</h1>
            <p class="lead text-muted">Explora todos nuestros productos e impresoras</p>
        </div>

        <div id="contenedor-productos" class="contenedor-productos">
            @foreach($products as $product)
                
                {{-- LÓGICA DE CLASE PARA PRODUCTOS AGOTADOS --}}
                <div class="tarjeta-producto {{ $product->stock <= 0 ? 'agotado' : '' }}" style="position: relative; overflow: hidden;">

                    {{-- OVERLAY SI ESTÁ AGOTADO --}}
                    @if($product->stock <= 0)
                        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.6); z-index: 5; display: flex; align-items: center; justify-content: center;">
                            <span style="background: #dc3545; color: white; padding: 10px 20px; font-weight: bold; transform: rotate(-15deg); font-size: 1.2rem; border-radius: 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">AGOTADO</span>
                        </div>
                    @endif

                    <img
                        src="{{ asset('img/' . $product->image) }}"
                        alt="{{ $product->name }}"
                        onerror="this.src='{{ asset('marcaDeAgua.png') }}'"
                        style="{{ $product->stock <= 0 ? 'filter: grayscale(100%); opacity: 0.5;' : '' }}"
                    >

                    <h3>{{ $product->name }}</h3>

                    <p class="producto-descripcion">
                        {{ $product->description }}
                    </p>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="producto-precio">
                            {{ number_format($product->price, 2) }} €
                        </span>
                        @if($product->stock > 0 && $product->stock <= 5)
                            <small class="text-danger fw-bold">¡Quedan {{ $product->stock }}!</small>
                        @endif
                    </div>

                    <a href="{{ route('products.show', $product->id) }}" class="btn {{ $product->stock <= 0 ? 'btn-secondary disabled' : 'btn-primary' }} w-100 mt-2">
                        {{ $product->stock <= 0 ? 'Sin Stock' : 'Ver Detalles y Opinar' }}
                    </a>

                </div>
            @endforeach
        </div>
    </div>
    <br>

</main>
@endsection