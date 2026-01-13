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

      <section class="hero" style="background: url('public/fondoPrincipio.jpg') no-repeat center center/cover;">
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

      <section class="creeper-hero" style="background: url('public/creeper.jpg') no-repeat center center/cover;">
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

      <section id="destacados" class="productos-destacados">
        <div class="container">
            <h1>Productos Destacados</h1>
            <div id="contenedor-productos" class="contenedor-productos"></div>
        </div>
      </section>
  
      <section id="impresoras" class="impresoras-section">
        <div class="container">
            <h1 class="impresoras-titulo">Nuestras Impresoras 3D</h1>
            <div id="contenedor-impresoras" class="contenedor-productos"></div>
        </div>
      </section>
      
      <section class="seccion-sobre-nosotros">
        <div class="container">
            <div class="row gy-4 sobre-nosotros-fila">
                <div class="col-12 col-md-12 col-lg-5 gif-sobre-nosotros">
                    <img src="public/impresora.gif" alt="GIF representativo de PrintHub" class="img-fluid" />
                </div>
                <div class="col-12 col-md-12 col-lg-7 texto-sobre-nosotros">
                    <h2>Sobre Nosotros</h2>
                    <p>En PrintHub, nos especializamos en la creación de maquetas personalizadas utilizando tecnología de impresión 3D de última generación...</p>
                    <p>Ya sea que busques una figura de tu videojuego favorito, una maqueta arquitectónica o un modelo de automóvil...</p>
                </div>
            </div>
        </div>
      </section>

      <section id="como-funciona" class="seccion-como-funciona">
        <div class="container contenedor-como-funciona">
            <div class="titulo-funciona">
                <h2>¿Tienes una idea? La hacemos realidad</h2>
                <p class="subtitulo-como-funciona">Nuestro proceso de diseño personalizado en 4 simples pasos.</p>
            </div>

            <div class="row g-4 pasos-como-funciona">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="tarjeta-paso h-100">
                        <span class="numero-paso">1</span>
                        <h3>Cuéntanos tu idea</h3>
                        <p>Rellena nuestro formulario de contacto con tu boceto, fotos o una simple descripción.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="tarjeta-paso h-100">
                        <span class="numero-paso">2</span>
                        <h3>Diseño y Boceto 3D</h3>
                        <p>Nuestro equipo creará un modelo 3D y te lo enviará para tu aprobación.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="tarjeta-paso h-100">
                        <span class="numero-paso">3</span>
                        <h3>Impresión y Calidad</h3>
                        <p>Usamos los mejores materiales (resina, PLA, PETG) para imprimir tu pieza con la máxima precisión.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="tarjeta-paso h-100">
                        <span class="numero-paso">4</span>
                        <h3>Recíbelo en casa</h3>
                        <p>Empaquetamos tu creación con cuidado y te la enviamos lista para disfrutar.</p>
                    </div>
                </div>
            </div>

            <div class="boton-container">
                <a href="php/contact.php" class="boton-cta-primario">Empezar mi Proyecto</a>
            </div>
        </div>
      </section>

      <section class="video">
        <div class="container">
            <div class="video-wrapper">
                <h2>Descubre Más Sobre PrintHub</h2>
                <div class="video-contenedor">
                    <video autoplay loop muted playsinline class="img-fluid">
                        <source src="public/Presentacion_Clientes.mp4" type="video/mp4" />
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
@endsection