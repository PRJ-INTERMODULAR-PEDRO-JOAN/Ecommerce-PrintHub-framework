<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrintHub - Acceso</title>
    <link href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loginStyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/registerStyle.css') }}">
    <style>
        body { background-color: #f0f2f5; display: flex; flex-direction: column; min-height: 100vh; }
        /* Ajuste para que la tarjeta quede perfectamente centrada */
        .auth-wrapper { flex: 1; display: flex; align-items: center; justify-content: center; padding: 20px; margin-top: 60px; }
        .nav-shadow { box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top nav-shadow">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('img/logoPrintHub.jpeg') }}" alt="Logo" width="35" height="35" class="rounded-circle me-2">
                <span class="fw-bold text-dark">PrintHub</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">🏠 Volver al Inicio</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="auth-wrapper">
        @yield('content')
    </div>

    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>