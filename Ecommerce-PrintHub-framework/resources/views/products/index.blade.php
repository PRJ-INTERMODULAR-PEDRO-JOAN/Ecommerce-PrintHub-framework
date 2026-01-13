@extends('layouts.legacy')

@section('title', 'Galeria')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/galeriaStyle.css') }}">
@endpush

@section('content')
    <h1 class="titulo-galeria">La Nostra Col·lecció</h1>

    <div class="gallery-container">
        @forelse($products as $product)
            <div class="card">
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="card-img">
                
                <div class="card-body">
                    <h3>{{ $product->name }}</h3>
                    <p class="price">{{ number_format($product->price, 2) }} €</p>
                    
                    <a href="{{ route('products.show', $product->id) }}" class="btn-buy">
                        Veure Detalls
                    </a>
                </div>
            </div>
        @empty
            <p style="text-align: center; width: 100%;">No hi ha productes disponibles.</p>
        @endforelse
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/galeria.js') }}"></script>
@endpush