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
                        👁️ Ver Detalles y Opinar
                    </a>

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
                <div class="tarjeta-producto">

                    <img
                        src="{{ asset('img/' . $impresora->image) }}"
                        alt="{{ $impresora->name }}"
                        onerror="this.src='{{ asset('marcaDeAgua.png') }}'"
                    >

                    <h3>{{ $impresora->name }}</h3>

                    <p class="producto-descripcion">
                        {{ $impresora->description }}
                    </p>

                    <span class="producto-precio">
                        {{ number_format($impresora->price, 2) }} €
                    </span>

                    <a href="{{ route('products.show', $impresora->id) }}" class="btn btn-primary w-100 mt-2">
                        👁️ Ver Detalles y Opinar
                    </a>

                </div>
            @endforeach
        </div>
    </div>
</section>


      <section class="seccion-sobre-nosotros">
        <div class="container">
            <div class="row gy-4 sobre-nosotros-fila">
                <div class="col-12 col-md-12 col-lg-5 gif-sobre-nosotros">
                    <img src="{{ asset('img/impresora.gif') }}" alt="GIF representativo de PrintHub" class="img-fluid" />
                </div>
                <div class="col-12 col-md-12 col-lg-7 texto-sobre-nosotros">
                    <h2>Sobre Nosotros</h2>
                    <p>En PrintHub, nos especializamos en la creación de maquetas personalizadas utilizando tecnología de impresión 3D de última generación...</p>
                    <p>Ya sea que busques una figura de tu videojuego favorito, una maqueta arquitectónica o un modelo de automóvil...</p>
                </div>
            </div>
        </div>
      </section>

      <section class="video">
        <div class="container">
            <div class="video-wrapper">
                <h2 class="text-black">Descubre Más Sobre PrintHub</h2>
                <div class="video-contenedor">
                    <video autoplay loop muted playsinline class="img-fluid">
                        <source src="{{ asset('img/Presentacion_Clientes_sin_con.mp4') }}" type="video/mp4" />
                    </video>
                </div>
            </div>
        </div>
      </section>
      <section class="seccion-testimonios">
        <div class="container">
            <h2>Qué dicen nuestros clientes</h2>
            <div class="row g-4 contenedor-testimonios">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="tarjeta-testimonio h-100">
                        <p class="cita-testimonio">"¡Increíble! Pedí una maqueta de mi coche soñado y el nivel de detalle es espectacular. 100% recomendado."</p>
                        <span class="autor-testimonio">- Carlos G.</span>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="tarjeta-testimonio h-100">
                        <p class="cita-testimonio">"El servicio de diseño personalizado es genial. Captaron mi idea a la primera y la figura de Aatrox quedó perfecta."</p>
                        <span class="autor-testimonio">- Laura M.</span>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="tarjeta-testimonio h-100">
                        <p class="cita-testimonio">"Compré mi primera impresora 3D aquí y el soporte fue excelente. Muy contenta con la Ender 3."</p>
                        <span class="autor-testimonio">- Javier R.</span>
                    </div>
                </div>
            </div>
        </div>
      </section>
</main>
@endsection