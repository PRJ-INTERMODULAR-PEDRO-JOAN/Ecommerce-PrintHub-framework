@extends('layouts.frontend')

@section('title', 'Catàleg')

@section('content')
<section id="destacados" class="productos-destacados">
    <div class="container">
        <h1>Catàleg de Productes</h1>
        
        <div class="mb-4 p-3 border rounded">
            <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label>Importar Excel:</label>
                <input type="file" name="excelFile" required>
                <button type="submit" class="btn btn-primary btn-sm">Pujar</button>
            </form>
        </div>

        <div class="contenedor-productos" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
            @foreach($products as $product)
                <div class="tarjeta-producto">
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                    <h3>{{ $product->name }}</h3>
                    <p>{{ $product->description }}</p>
                    <p class="precio">{{ $product->price }} €</p>
                    <button class="boton">Afegir al carret</button>
                    
                    <div class="mt-2">
                        <input type="text" id="comment-{{ $product->id }}" placeholder="Comentari..." class="form-control mb-1">
                        <button onclick="postComment({{ $product->id }})" class="btn btn-sm btn-secondary">Comentar</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
async function postComment(productId) {
    const text = document.getElementById(`comment-${productId}`).value;
    
    try {
        const response = await fetch(`/api/products/${productId}/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ text: text, rating: 5 }) // Rating fix per ara
        });
        
        const data = await response.json();
        if(response.ok) {
            alert('Comentari enviat!');
        } else {
            alert('Error: ' + JSON.stringify(data));
        }
    } catch (error) {
        console.error(error);
    }
}
</script>
@endpush