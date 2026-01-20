@extends('layouts.legacy')

@section('title', 'Inicio')

@section('content')
<main class="contenido-principal">

    <a href="#destacados" class="enlace-banner" style="display:block;">
        <div class="banner">
          <div class="banner-texto">
            ¡Oferta especial de Navidad! 🦌🎅 Solo por tiempo limitado.
          </div>
        </div>
      </a>

      <section class="hero" style="background: url('{{ asset('img/fondoPrincipio.jpg') }}') no-repeat center center/cover;">
        <div class="container h-100">
            <div class="row h-100 hero-fila">
                <div class="col-12 hero-contenido">
                    <h1>Bienvenido/a a Print<span class="resaltado">Hub</span></h1>
                    <p>Innovación y calidad en cada producto</p>
                    <a href="#destacados" class="flecha">&#x25BC;</a>
                </div>
            </div>
        </div>
      </section>

      <section id="destacados" class="productos-destacados pt-5">
        <div class="container">
            <h1>Productos Destacados</h1>
            <div id="contenedor-productos" class="contenedor-productos">
                @foreach($destacados as $product)
                    <div class="card producto-card">
                        <img src="{{ asset('img/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-truncate">{{ $product->description }}</p>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="precio">{{ $product->price }} €</span>
                                <button class="btn btn-primary btn-sm">Ver detalles</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
      </section>

      <section id="impresoras" class="impresoras-section">
        <div class="container">
            <h1 class="impresoras-titulo">Nuestras Impresoras 3D</h1>
            <div id="contenedor-impresoras" class="contenedor-productos">
                @foreach($impresoras as $impresora)
                    <div class="card producto-card">
                        <img src="{{ asset('img/' . $impresora->image) }}" class="card-img-top" alt="{{ $impresora->name }}">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $impresora->name }}</h5>
                            <p class="card-text text-truncate">{{ $impresora->description }}</p>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="precio">{{ $impresora->price }} €</span>
                                <button class="btn btn-primary btn-sm w-100">Comprar</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
      </section>

      <section class="video">
        <div class="container">
            <div class="video-wrapper">
                <h2 class="text-white">Descubre Más Sobre PrintHub</h2>
                <div class="video-contenedor">
                    <video autoplay loop muted playsinline class="img-fluid">
                        <source src="{{ asset('img/Presentacion_Clientes.mp4') }}" type="video/mp4" />
                    </video>
                </div>
            </div>
        </div>
      </section>
</main>
@endsection