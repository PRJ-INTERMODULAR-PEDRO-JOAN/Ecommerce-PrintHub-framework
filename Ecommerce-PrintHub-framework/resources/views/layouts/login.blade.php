<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrintHub - @yield('title')</title>
    
    <link rel="stylesheet" href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}">
    
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
        <div class="logo"><img src="public/logoPrintHub.jpeg" alt="Logo de PrintHub" /></div>
    </div>
    <ul class="iconos-utilidad">
        <li><a href="#" aria-label="Carrito">🛒</a></li>
        <li><a href="auth/profile.php" aria-label="Iniciar Sesión">👤</a></li>
    </ul>
    <h3 class="etiqueta-menu">Menú</h3>
    <nav>
        <ul>
            <li><a href="/">Inicio</a></li>
            <li class="desplegable">
                <a href="#">Maquetas Personalizadas ▾</a>
                <ul class="contenido-desplegable">
                    <li><a href="#">Videojuegos</a></li>
                    <li><a href="#">Arquitectura</a></li>
                    <li><a href="#">Automóviles</a></li>
                </ul>
            </li>
            <li><a href="#como-funciona">Diseñar Maquetas</a></li>
            <li><a href="src/galeria.html">Galería de Proyectos</a></li>
            <li><a href="#impresoras">Impresoras 3D</a></li>
            <li><a href="php/contact.php">Formulario Contacto</a></li>
            <li><a href="/importar">Importar Información</a></li>
        </ul>
    </nav>
</aside>

    <main>
        @yield('content')
    </main>

    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/barra-lateral.js') }}"></script>
    @stack('scripts')
</body>
</html>