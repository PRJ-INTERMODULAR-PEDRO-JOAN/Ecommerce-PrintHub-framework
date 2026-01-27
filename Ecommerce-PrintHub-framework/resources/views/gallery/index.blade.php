@extends('layouts.legacy')

@section('title', 'Galería de Proyectos')

@section('content')

    <div class="banner">
        <div class="banner-texto">
            ¡Oferta especial de Navidad! 🦌🎅 Solo por tiempo limitado.
        </div>
    </div>

    <header>
        <h1>GALERÍA DE PROYECTOS</h1>
        <img src="{{ asset('img/logoPrintHub.jpeg') }}" alt="Logo PrintHub" />
    </header>

    <div class="contenedor">
        <div class="menu">
            <ul>
                <li><a href="#">Todo</a></li>
                @foreach($secciones as $seccion)
                    <li><a href="#{{ $seccion['id'] }}">{{ ucfirst(strtolower($seccion['titulo'])) }}</a></li>
                @endforeach
                <li><a href="#">Modelos</a></li>
                <li><a href="#">Otros</a></li>
            </ul>
        </div>

        <div class="galeria">
            @foreach($secciones as $seccion)
            <section class="carousel-section" id="{{ $seccion['id'] }}">
                <h2>{{ $seccion['titulo'] }}</h2>
                
                <div class="carousel" aria-label="Carrusel de {{ $seccion['titulo'] }}">
                    <button class="carousel-btn prev" aria-label="Anterior">&#10094;</button>
                    
                    <div class="carousel-viewport">
                        <div class="carousel-track">
                            @foreach($seccion['imagenes'] as $imagen)
                            <div class="slide">
                                <img src="{{ asset('img/' . $imagen) }}" 
                                     alt="{{ $seccion['titulo'] }}" 
                                     onerror="console.log('Error cargando: ' + this.src)" />
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <button class="carousel-btn next" aria-label="Siguiente">&#10095;</button>
                    <div class="dots" role="tablist"></div>
                </div>
            </section>
            @endforeach
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/galeria.js') }}"></script>
@endpush