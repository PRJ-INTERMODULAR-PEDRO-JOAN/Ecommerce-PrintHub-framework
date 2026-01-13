@extends('layouts.legacy')

@section('title', $product->name)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/productDetail.css') }}">
@endpush

@section('content')
    <div class="product-detail-container">
        <div class="left-column">
            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="main-image">
        </div>
        
        <div class="right-column">
            <h2 class="product-title">{{ $product->name }}</h2>
            <p class="product-sku">SKU: {{ $product->sku }}</p>
            <p class="product-price">{{ number_format($product->price, 2) }} €</p>
            
            <div class="product-description">
                <p>{{ $product->description }}</p>
            </div>
            
            <p class="stock-status">Stock disponible: {{ $product->stock }}</p>
            
            <button class="add-to-cart-btn">Afegir al Carret</button>
        </div>
    </div>

    <div class="comments-section" style="padding: 20px; max-width: 1200px; margin: 0 auto;">
        <h3>Comentaris</h3>
        <div id="comments-placeholder">
            <p>Properament disponible...</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/productDetail.js') }}"></script>
@endpush