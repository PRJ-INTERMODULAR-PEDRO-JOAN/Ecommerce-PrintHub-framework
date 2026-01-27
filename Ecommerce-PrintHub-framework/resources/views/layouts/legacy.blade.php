<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrintHub - @yield('title')</title>
    
    <link rel="stylesheet" href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/galeriaStyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/aside.css') }}">
    
    @stack('styles')
</head>
<body>

<button class="alternar-menu">☰</button>

<aside class="barra-lateral">
    <div class="barra-lateral-cabecera">
        <h1 class="logo-texto">Print<span class="resaltado">Hub</span></h1>
        <div class="logo"><img src="{{ asset('img/logoPrintHub.jpeg') }}" alt="Logo de PrintHub" /></div>
    </div>
    <ul class="iconos-utilidad">
        <li>
            <a href="{{ route('cart.index') }}" aria-label="Carrito" class="position-relative text-decoration-none">
                🛒
                @if(session('cart'))
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.5em;">
                        {{ count(session('cart')) }}
                    </span>
                @endif
            </a>
        </li>
        
        @auth
            <li class="nav-item dropdown ms-3" style="list-style: none;">
                <a class="nav-link dropdown-toggle text-black" href="#" role="button" data-bs-toggle="dropdown">
                    {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('dashboard') }}">Mi Perfil</a></li>
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Editar Datos</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">Cerrar Sesión</button>
                        </form>
                    </li>
                </ul>
            </li>
        @else
            <li class="nav-item ms-2" style="list-style: none;">
                <a class="btn btn-outline-light btn-sm px-3" href="{{ route('login') }}">
                    👤 Login
                </a>
            </li>
        @endauth
    </ul>
    
    <h3 class="etiqueta-menu">Menú</h3>
    <nav>
        <ul>
            <li><a href="{{ route('home') }}">Inicio</a></li>
            <li class="desplegable">
                <a href="#">Maquetas Personalizadas ▾</a>
                <ul class="contenido-desplegable">
                    <li><a href="">Videojuegos</a></li>
                    <li><a href="#">Arquitectura</a></li>
                    <li><a href="#">Automóviles</a></li>
                </ul>
            </li>
            <li><a href="{{ route('products.list') }}">Todos Nuestros Productos</a></li>
            <li><a href="#como-funciona">Diseñar Maquetas</a></li>
            <li><a href="{{ route('gallery.index') }}">Galería de Proyectos</a></li>
            <li><a href="#impresoras">Impresoras 3D</a></li>
            <li><a href="{{ asset('php/contact.php') }}">Formulario Contacto</a></li>
             @auth
                @if(Auth::user()->role === 'admin')
                    <li>
                        <a href="{{ route('admin.import') }}" style="color: black;">
                            Importar Productos
                        </a>
                    </li>
                @endif
            @endauth
        </ul>
    </nav>
</aside>

    <main>
        @yield('content')
    </main>

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
                <img src="{{ asset('img/fotterImage.jpg') }}" alt="Imagen Global 3D" />
            </div>
            <div class="pie-pagina-derecha">
                <div class="servicios">
                  <h3>Servicios y productos</h3>
                  <ul>
                    <li><a href="#">Fabricación aditiva 3D</a></li>
                    <li><a href="#">Servicio de Impresión 3D</a></li>
                    <li><a href="#">Prototipado 3D</a></li>
                    <li><a href="#">Ingeniería y diseño</a></li>
                  </ul>
                </div>
                <div class="contacto">
                  <h3>Contacto</h3>
                  <ul>
                    <li>📞 (+34) 951 753 852</li>
                    <li>📧 <a href="mailto:printhub@contact.me">printhub@contact.me</a></li>
                    <li>📍 Partida Cotes Altes, 27, 03804 Alcoi, Alicante</li>
                    <li>🕒 Lunes – Viernes: 8:00 – 13:30</li>
                  </ul>
                </div>
            </div>
        </div>
      </footer>

    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/barra-lateral.js') }}"></script>
    @stack('scripts')
</body>
</html> 