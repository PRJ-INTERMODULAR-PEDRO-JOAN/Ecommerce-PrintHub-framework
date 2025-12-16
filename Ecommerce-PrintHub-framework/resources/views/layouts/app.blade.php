<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PrintHub</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/aside.css') }}" />
    
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&family=Roboto+Mono:wght@700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body>
    <button class="alternar-menu" onclick="document.querySelector('.barra-lateral').classList.toggle('activo')">☰</button>

    <aside class="barra-lateral">
        <div class="barra-lateral-cabecera">
            <h1 class="logo-texto">Print<span class="resaltado">Hub</span></h1>
            <div class="logo"><img src="{{ asset('img/logoPrintHub.jpeg') }}" alt="Logo de PrintHub" /></div>
        </div>
        
        <ul class="iconos-utilidad">
            <li><a href="#" aria-label="Carrito">🛒</a></li>
            @auth
                <li><a href="{{ url('/profile') }}" aria-label="Perfil">👤</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" style="background:none; border:none; cursor:pointer;" title="Cerrar Sesión">🚪</button>
                    </form>
                </li>
            @else
                <li><a href="{{ route('login') }}" aria-label="Iniciar Sesión">👤</a></li>
            @endauth
        </ul>

        <h3 class="etiqueta-menu">Menú</h3>
        <nav>
            <ul>
                <li><a href="{{ url('/') }}">Inicio</a></li>
                <li><a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.index') ? 'activo' : '' }}">Productos</a></li>
                <li class="desplegable">
                    <a href="#">Maquetas Personalizadas ▾</a>
                    <ul class="contenido-desplegable">
                        <li><a href="#">Videojuegos</a></li>
                        <li><a href="#">Arquitectura</a></li>
                        <li><a href="#">Automóviles</a></li>
                    </ul>
                </li>
                <li><a href="#">Diseñar Maquetas</a></li>
                <li><a href="#">Galería de Proyectos</a></li>
                <li><a href="#">Impresoras 3D</a></li>
                <li><a href="#">Contacto</a></li>
            </ul>
        </nav>
    </aside>

    <main class="contenido-principal">
        
        {{ $slot }}

        <footer class="pie-pagina">
            <div class="pie-pagina-contenedor">
                <div class="pie-pagina-izquierda">
                    <h2 class="logo">Print<span class="resaltado">Hub</span></h2><br />
                    <div>
                      <a class="iconos-sociales" href="#"><img src="{{ asset('img/facebook.svg') }}" alt="Facebook"/></a>
                      <a class="iconos-sociales" href="#"><img src="{{ asset('img/linkedin.svg') }}" alt="LinkedIn"/></a>
                      <a class="iconos-sociales" href="#"><img src="{{ asset('img/youtube.svg') }}" alt="YouTube"/></a>
                      <a class="iconos-sociales" href="#"><img src="{{ asset('img/insta.svg') }}" alt="Instagram"/></a>
                    </div>
                </div>
                <div class="pie-pagina-centro">
                    <img src="{{ asset('img/fotterImage.jpg') }}" alt="Imagen global 3D" />
                </div>
                <div class="pie-pagina-derecha">
                    <div class="servicios">
                      <h3>Servicios y productos</h3>
                      <ul>
                        <li><a href="#">Fabricación aditiva 3D</a></li>
                        <li><a href="#">Servicio de Impresión 3D</a></li>
                      </ul>
                    </div>
                    <div class="contacto">
                      <h3>Contacto</h3>
                      <ul>
                        <li>📞 (+34) 951 753 852</li>
                        <li>📧 info@printhub.com</li>
                        <li>📍 Partida Cotes Altes, 27, 03804 Alcoi</li>
                      </ul>
                    </div>
                </div>
            </div>
        </footer>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/barra-lateral.js') }}"></script>
  </body>
</html>