@extends('layouts.legacy')

@section('title', 'Inicio')

@section('content')
<main class="contenido-principal">
    
    {{-- Banner Superior --}}
    <a href="#destacados" class="enlace-banner" style="display:block;">
        <div class="banner">
          <div class="banner-texto">
            ¡Oferta especial de Navidad! 🦌🎅 Solo por tiempo limitado.
          </div>
        </div>
    </a>

    {{-- Hero Section --}}
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

    {{-- ======================================================= --}}
    {{-- 🔥 SECCIÓN OFERTA FLASH DEL DÍA (Banner Grande) 🔥 --}}
    {{-- ======================================================= --}}
    @if(isset($ofertaDia) && $ofertaDia)
    <section class="py-5 bg-dark text-white" style="background: linear-gradient(135deg, #1a1a1a, #2c3e50);">
        <div class="container">
            <div class="row align-items-center bg-white rounded-4 shadow overflow-hidden text-dark p-0">
                
                {{-- Imagen del Producto --}}
                <div class="col-md-6 p-0 position-relative" style="background: #f8f9fa; min-height: 400px; display: flex; align-items: center; justify-content: center;">
                    <div class="position-absolute top-0 start-0 bg-danger text-white fw-bold px-4 py-2 shadow" style="font-size: 1.5rem; z-index: 10; border-bottom-right-radius: 10px;">
                        🔥 -50% HOY
                    </div>
                    <img src="{{ asset('img/' . $ofertaDia->image) }}" alt="{{ $ofertaDia->name }}" class="img-fluid" style="max-height: 350px; object-fit: contain;">
                </div>

                {{-- Textos y Precio --}}
                <div class="col-md-6 p-5 text-center text-md-start">
                    <h4 class="text-danger fw-bold text-uppercase mb-2">⚡ Oferta Flash Exclusiva</h4>
                    <h2 class="display-4 fw-bold mb-3">{{ $ofertaDia->name }}</h2>
                    <p class="lead text-muted mb-4">{{ Str::limit($ofertaDia->description, 120) }}</p>
                    
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-3 mb-4">
                        <div class="text-decoration-line-through text-muted fs-3">{{ number_format($ofertaDia->price, 2) }} €</div>
                        <div class="text-danger fw-bold display-3 animate-price">{{ number_format($ofertaDia->price / 2, 2) }} €</div>
                    </div>

                    <div class="d-flex flex-column flex-md-row gap-3 justify-content-center justify-content-md-start">
                      <a href="{{ route('products.show', $ofertaDia->id) }}" class="btn btn-outline-dark btn-lg rounded-pill px-4">
                          Ver Detalles
                      </a>
                      <a href="{{ route('cart.add', $ofertaDia->id) }}" class="btn btn-danger btn-lg rounded-pill px-5 shadow fw-bold animate-pulse">
                          ¡COMPRAR AHORA! 🛒
                      </a>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top small text-muted">
                        * Oferta válida solo durante el día de hoy. Stock limitado: <strong>{{ $ofertaDia->stock }} unidades</strong>.
                    </div>
                </div>

            </div>
        </div>
    </section>
    
    <style>
        .animate-pulse { animation: pulse 2s infinite; }
        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
            50% { transform: scale(1.05); box-shadow: 0 0 0 15px rgba(220, 53, 69, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
        }
    </style>
    @endif


    {{-- ======================================================= --}}
    {{-- LISTA DE DESTACADOS --}}
    {{-- ======================================================= --}}
    <section id="destacados" class="productos-destacados pt-5">
        <div class="container">
            <h1>Productos Destacados</h1>

            <div id="contenedor-productos" class="contenedor-productos">
                @foreach($destacados as $product)
                    {{-- LÓGICA: Comprobamos si este producto es la oferta --}}
                    @php
                        $isDeal = (isset($ofertaDia) && $ofertaDia && $ofertaDia->id === $product->id);
                        $finalPrice = $isDeal ? ($product->price / 2) : $product->price;
                    @endphp

                    <div class="tarjeta-producto {{ $product->stock <= 0 ? 'agotado' : '' }}" style="position: relative;">

                        {{-- ETIQUETAS FLOTANTES --}}
                        @if($product->stock <= 0)
                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.6); z-index: 5; display: flex; align-items: center; justify-content: center;">
                                <span style="background: #dc3545; color: white; padding: 10px 20px; font-weight: bold; transform: rotate(-15deg); font-size: 1.2rem; border-radius: 5px;">AGOTADO</span>
                            </div>
                        @elseif($isDeal)
                            {{-- ETIQUETA PEQUEÑA DE OFERTA EN LA TARJETA --}}
                            <div style="position: absolute; top: 10px; right: 10px; z-index: 4;">
                                <span class="badge bg-danger shadow animate-pulse">🔥 -50%</span>
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

                        {{-- PRECIO: Si es oferta, tachamos el viejo --}}
                        <div class="mb-2">
                            @if($isDeal)
                                <span class="text-decoration-line-through text-muted small me-2">
                                    {{ number_format($product->price, 2) }} €
                                </span>
                                <span class="producto-precio text-danger fw-bold">
                                    {{ number_format($finalPrice, 2) }} €
                                </span>
                            @else
                                <span class="producto-precio">
                                    {{ number_format($product->price, 2) }} €
                                </span>
                            @endif
                        </div>

                        <a href="{{ route('products.show', $product->id) }}" class="btn {{ $product->stock <= 0 ? 'btn-secondary disabled' : 'btn-primary' }} w-100 mt-2">
                            Ver Detalles y Opinar
                        </a>

                    </div>
                @endforeach
            </div>
        </div>
    </section>


    {{-- ======================================================= --}}
    {{-- LISTA DE IMPRESORAS --}}
    {{-- ======================================================= --}}
    <section id="impresoras" class="impresoras-section">
        <div class="container">
            <h1 class="impresoras-titulo">Nuestras Impresoras 3D</h1>

            <div id="contenedor-impresoras" class="contenedor-productos">
                @foreach($impresoras as $impresora)
                    {{-- LÓGICA: Comprobamos si es la oferta --}}
                    @php
                        $isDeal = (isset($ofertaDia) && $ofertaDia && $ofertaDia->id === $impresora->id);
                        $finalPrice = $isDeal ? ($impresora->price / 2) : $impresora->price;
                    @endphp

                    <div class="tarjeta-producto {{ $impresora->stock <= 0 ? 'agotado' : '' }}" style="position: relative;">

                        @if($impresora->stock <= 0)
                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.6); z-index: 5; display: flex; align-items: center; justify-content: center;">
                                <span style="background: #dc3545; color: white; padding: 10px 20px; font-weight: bold; transform: rotate(-15deg); font-size: 1.2rem; border-radius: 5px;">AGOTADO</span>
                            </div>
                        @elseif($isDeal)
                             <div style="position: absolute; top: 10px; right: 10px; z-index: 4;">
                                <span class="badge bg-danger shadow animate-pulse">🔥 -50%</span>
                            </div>
                        @endif

                        <img
                            src="{{ asset('img/' . $impresora->image) }}"
                            alt="{{ $impresora->name }}"
                            onerror="this.src='{{ asset('marcaDeAgua.png') }}'"
                            style="{{ $impresora->stock <= 0 ? 'filter: grayscale(100%); opacity: 0.5;' : '' }}"
                        >

                        <h3>{{ $impresora->name }}</h3>

                        <p class="producto-descripcion">
                            {{ $impresora->description }}
                        </p>

                        <div class="mb-2">
                            @if($isDeal)
                                <span class="text-decoration-line-through text-muted small me-2">
                                    {{ number_format($impresora->price, 2) }} €
                                </span>
                                <span class="producto-precio text-danger fw-bold">
                                    {{ number_format($finalPrice, 2) }} €
                                </span>
                            @else
                                <span class="producto-precio">
                                    {{ number_format($impresora->price, 2) }} €
                                </span>
                            @endif
                        </div>

                        <a href="{{ route('products.show', $impresora->id) }}" class="btn {{ $impresora->stock <= 0 ? 'btn-secondary disabled' : 'btn-primary' }} w-100 mt-2">
                             Ver Detalles y Opinar
                        </a>

                    </div>
                @endforeach
            </div>
        </div>
    </section>

      {{-- SECCIONES EXTRA (Sobre nosotros, vídeo, testimonios...) --}}
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

    {{-- WIDGET DEL CHATBOT --}}
    <link rel="stylesheet" href="https://www.gstatic.com/dialogflow-console/fast/df-messenger/prod/v1/themes/df-messenger-default.css">
    <script src="https://www.gstatic.com/dialogflow-console/fast/df-messenger/prod/v1/df-messenger.js"></script>
    <df-messenger
        project-id="COSAS"
        agent-id="COSAS"
        language-code="es"
        max-query-length="-1">
        <df-messenger-chat-bubble
        chat-title="Asistente PrintHub">
        </df-messenger-chat-bubble>
    </df-messenger>
    
    <style>
        df-messenger {
            z-index: 999;
            position: fixed;
            bottom: 16px;
            right: 16px;
        }
    </style>

</main>
@endsection