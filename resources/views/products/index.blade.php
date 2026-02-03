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

                <div class="tarjeta-producto">

                    <img
                        src="{{ asset('img/' . $product->image) }}"
                        alt="{{ $product->name }}"
                        onerror="this.src='{{ asset('marcaDeAgua.png') }}'"
                    >

                    <h3>{{ $product->name }}</h3>

                    <p class="producto-descripcion">
                        {{ $product->description }}
                    </p>

                    <span class="producto-precio">
                        {{ number_format($product->price, 2) }} €
                    </span>

                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary w-100 mt-2">
                        Ver Detalles y Opinar
                    </a>

                </div>
            @endforeach
        </div>
    </div>
    <br>

</main>
@endsection
