@extends('layouts.legacy')

@section('title', 'Inici')

@section('content')
<main class="contenido-principal">
      
      <a href="#destacados" class="enlace-banner">
        <div class="banner">
          <div class="banner-texto">
            ¡Oferta especial de Navidad! 🦌🎅 Solo por tiempo limitado, hasta el 31 de diciembre.
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

      <section class="creeper-hero" style="background: url('{{ asset('img/creeper.jpg') }}') no-repeat center center/cover;">
        <div class="container h-100">
            <div class="row h-100 creeper-fila">
                <div class="col-12 creeper-contenido">
                    <h1>NUEVO CREEPER XXL</h1>
                    <p>Suéltalo que explota</p>
                    <div class="creeper-botones">
                        <button class="boton-creeper">MÁS INFORMACIÓN</button>
                        <button class="boton-creeper">COMPRAR</button>
                    </div>
                </div>
            </div>
        </div>
      </section>

      <section id="destacados" class="productos-destacados py-5">
        <div class="container">
            <h1 class="text-center mb-4">Productos Destacados</h1>
            
            <div class="row g-4">
                @foreach($destacados as $product)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div style="height: 250px; overflow: hidden;" class="d-flex align-items-center justify-content-center bg-white rounded-top">
                            <img src="{{ asset('img/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="max-height: 100%; width: auto; object-fit: contain;">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-muted small text-truncate">{{ $product->description }}</p>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="fs-5 fw-bold text-primary">{{ $product->price }} €</span>
                                <button class="btn btn-outline-primary btn-sm">Ver detalles</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
      </section>
  
      <section id="impresoras" class="impresoras-section py-5 bg-light">
        <div class="container">
            <h1 class="impresoras-titulo text-center mb-4">Nuestras Impresoras 3D</h1>
            
            <div class="row g-4">
                @foreach($impresoras as $impresora)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow border-primary">
                        <div style="height: 250px; overflow: hidden;" class="d-flex align-items-center justify-content-center bg-white rounded-top">
                            <img src="{{ asset('img/' . $impresora->image) }}" class="card-img-top" alt="{{ $impresora->name }}" style="max-height: 100%; width: auto; object-fit: contain;">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $impresora->name }}</h5>
                            <p class="card-text small">{{ Str::limit($impresora->description, 80) }}</p>
                            <p class="card-text fw-bold text-end text-primary fs-4">{{ $impresora->price }} €</p>
                        </div>
                        <div class="card-footer bg-white border-0 pb-3">
                            <button class="btn btn-primary w-100">Comprar Ahora</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
      </section>
      
      <section class="seccion-sobre-nosotros py-5">
        <div class="container">
            <div class="row gy-4 sobre-nosotros-fila align-items-center">
                <div class="col-12 col-md-12 col-lg-5 gif-sobre-nosotros text-center">
                    <img src="{{ asset('img/impresora.gif') }}" alt="GIF representativo de PrintHub" class="img-fluid rounded shadow" />
                </div>
                <div class="col-12 col-md-12 col-lg-7 texto-sobre-nosotros">
                    <h2>Sobre Nosotros</h2>
                    <p>En PrintHub, nos especializamos en la creación de maquetas personalizadas utilizando tecnología de impresión 3D de última generación...</p>
                    <p>Ya sea que busques una figura de tu videojuego favorito, una maqueta arquitectónica o un modelo de automóvil...</p>
                </div>
            </div>
        </div>
      </section>

      <section id="como-funciona" class="seccion-como-funciona py-5">
        <div class="container contenedor-como-funciona">
            <div class="titulo-funciona text-center mb-5">
                <h2>¿Tienes una idea? La hacemos realidad</h2>
                <p class="subtitulo-como-funciona">Nuestro proceso de diseño personalizado en 4 simples pasos.</p>
            </div>

            <div class="row g-4 pasos-como-funciona text-center">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="tarjeta-paso h-100 p-3">
                        <span class="numero-paso d-block fs-1 fw-bold text-primary">1</span>
                        <h3>Cuéntanos tu idea</h3>
                        <p>Rellena nuestro formulario de contacto con tu boceto, fotos o una simple descripción.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="tarjeta-paso h-100 p-3">
                        <span class="numero-paso d-block fs-1 fw-bold text-primary">2</span>
                        <h3>Diseño y Boceto 3D</h3>
                        <p>Nuestro equipo creará un modelo 3D y te lo enviará para tu aprobación.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="tarjeta-paso h-100 p-3">
                        <span class="numero-paso d-block fs-1 fw-bold text-primary">3</span>
                        <h3>Impresión y Calidad</h3>
                        <p>Usamos los mejores materiales (resina, PLA, PETG) para imprimir tu pieza con la máxima precisión.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="tarjeta-paso h-100 p-3">
                        <span class="numero-paso d-block fs-1 fw-bold text-primary">4</span>
                        <h3>Recíbelo en casa</h3>
                        <p>Empaquetamos tu creación con cuidado y te la enviamos lista para disfrutar.</p>
                    </div>
                </div>
            </div>

            <div class="boton-container text-center mt-5">
                <a href="#" class="btn btn-primary btn-lg boton-cta-primario">Empezar mi Proyecto</a>
            </div>
        </div>
      </section>

      <section class="video py-5 bg-dark text-white">
        <div class="container">
            <div class="video-wrapper text-center">
                <h2 class="mb-4 text-white">Descubre Más Sobre PrintHub</h2>
                <div class="video-contenedor ratio ratio-16x9 mx-auto" style="max-width: 900px;">
                    <video autoplay loop muted playsinline class="img-fluid rounded">
                        <source src="{{ asset('img/Presentacion_Clientes.mp4') }}" type="video/mp4" />
                        Tu navegador no soporta el tag de video.
                    </video>
                </div>
            </div>
        </div>
      </section>

      <section class="seccion-testimonios py-5">
        <div class="container">
            <h2 class="text-center mb-5">Qué dicen nuestros clientes</h2>
            <div class="row g-4 contenedor-testimonios">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="tarjeta-testimonio card h-100 p-4 shadow-sm">
                        <p class="cita-testimonio fst-italic">"¡Increíble! Pedí una maqueta de mi coche soñado y el nivel de detalle es espectacular. 100% recomendado."</p>
                        <span class="autor-testimonio fw-bold text-end d-block mt-3">- Carlos G.</span>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="tarjeta-testimonio card h-100 p-4 shadow-sm">
                        <p class="cita-testimonio fst-italic">"El servicio de diseño personalizado es genial. Captaron mi idea a la primera y la figura de Aatrox quedó perfecta."</p>
                        <span class="autor-testimonio fw-bold text-end d-block mt-3">- Laura M.</span>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="tarjeta-testimonio card h-100 p-4 shadow-sm">
                        <p class="cita-testimonio fst-italic">"Compré mi primera impresora 3D aquí y el soporte fue excelente. Muy contenta con la Ender 3."</p>
                        <span class="autor-testimonio fw-bold text-end d-block mt-3">- Javier R.</span>
                    </div>
                </div>
            </div>
        </div>
      </section>
</main>
@endsection