<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrintHub - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/aside.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('logoPrintHubIcon.ico') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <button class="alternar-menu">☰</button>

    <aside class="barra-lateral">
        <div class="barra-lateral-cabecera">
            <h1 class="logo-texto">Print<span class="resaltado">Hub</span></h1>
            <div class="logo"><img src="{{ asset('logoPrintHub.jpeg') }}" alt="Logo" /></div>
        </div>
        <nav>
            <ul>
                <li><a href="{{ url('/') }}">Inicio</a></li>
                <li><a href="{{ route('products.index') }}">Catálogo</a></li>
                <li><a href="{{ url('/login') }}">Login</a></li>
            </ul>
        </nav>
    </aside>

    <main class="contenido-principal">
        @if(session('success'))
            <div class="alert alert-success m-3">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger m-3">{{ session('error') }}</div>
        @endif

        @yield('content')

        <footer class="pie-pagina">
            <div class="pie-pagina-contenedor">
                <p>&copy; 2025 PrintHub</p>
            </div>
        </footer>
    </main>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/barra-lateral.js') }}"></script>
    @stack('scripts')
</body>
</html>