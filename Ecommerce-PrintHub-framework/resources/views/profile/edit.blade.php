<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - PrintHub</title>
    <link href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/registerStyle.css') }}">
    <style>
        body { background-color: #f8f9fa; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .back-btn-wrapper { position: absolute; top: 20px; left: 20px; }
    </style>
</head>
<body>

    <div class="back-btn-wrapper">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold">
            ← Volver
        </a>
    </div>

    <div class="card shadow-lg border-0 rounded-4" style="max-width: 600px; width: 95%;">
        <div class="card-body p-5">
            
            <div class="text-center mb-4">
                <img src="{{ asset('img/logoPrintHub.jpeg') }}" width="60" class="rounded-circle mb-3 shadow-sm">
                <h3 class="fw-bold">Editar mis datos</h3>
                <p class="text-muted">Actualiza tu información personal</p>
            </div>

            @if (session('status') === 'profile-updated')
                <div class="alert alert-success text-center py-2 mb-4 rounded-3 small">
                    ¡Cambios guardados con éxito!
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Nombre</label>
                        <input type="text" name="name" class="form-control bg-light border-0 py-2" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Apellidos</label>
                        <input type="text" name="surname" class="form-control bg-light border-0 py-2" value="{{ old('surname', $user->surname) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold text-muted">Email</label>
                        <input type="email" name="email" class="form-control bg-light border-0 py-2" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold text-muted">Teléfono</label>
                        <input type="tel" name="phone" class="form-control bg-light border-0 py-2" value="{{ old('phone', $user->phone) }}">
                    </div>
                </div>

                <div class="mt-4 pt-2">
                    <button type="submit" class="btn btn-dark w-100 py-2 fw-bold rounded-3">
                        GUARDAR CAMBIOS
                    </button>
                </div>
            </form>

            <hr class="my-4 text-muted opacity-25">

            <div class="d-grid">
                <button class="btn btn-light text-muted border-0 py-2" type="button" data-bs-toggle="collapse" data-bs-target="#passCollapse">
                    🔒 Cambiar mi contraseña
                </button>
                <div class="collapse mt-3" id="passCollapse">
                    <div class="card card-body bg-light border-0 rounded-3">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>