<x-app-layout>
    <a href="#" class="enlace-banner">
        <div class="banner">
          <div class="banner-texto">
            ¡Oferta especial de Navidad! 🦌🎅 Solo por tiempo limitado.
          </div>
        </div>
    </a>

    <section class="hero" style="background: url('{{ asset("img/fondoPrincipio.jpg") }}') no-repeat center center/cover;">
        <div class="container h-100">
            <div class="row h-100 hero-fila">
                <div class="col-12 hero-contenido">
                    <h1>Nuestros <span class="resaltado">Productos</span></h1>
                    <p>Calidad e innovación en cada impresión</p>
                </div>
            </div>
        </div>
    </section>

    <section id="destacados" class="productos-destacados">
        <div class="container">
            <h1>Catálogo Completo</h1>
            
            <div class="contenedor-productos">
                @forelse($products as $product)
                    <div class="tarjeta-producto">
                        @if($product->image)
                            <img src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset($product->image) }}" alt="{{ $product->name }}" />
                        @else
                            <img src="{{ asset('img/logoPrintHub.jpeg') }}" alt="Sin imagen" />
                        @endif

                        <div class="info-producto">
                            <h3>{{ $product->name }}</h3>
                            <p class="descripcion descripcion-estilo">
                                {{ Str::limit($product->description, 50) }}
                            </p>
                            <div class="precio-accion">
                                <span class="precio">{{ $product->price }}€</span>
                                <button class="boton-comprar">Comprar</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: '1 / -1'; text-align:center; padding: 2rem;">
                        <h3>No hay productos disponibles</h3>
                        <p>Intenta importar productos o añadirlos a la base de datos.</p>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $products->links() }}
            </div>
        </div>
    </section>
</x-app-layout>