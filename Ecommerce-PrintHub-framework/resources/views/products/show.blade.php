@extends('layouts.legacy')

@section('title', $product->name)

@section('content')
<main class="contenido-principal" style="padding-top: 120px; min-height: 100vh;">
    <div class="container">
        
        <a href="{{ route('home') }}" class="btn btn-outline-secondary mb-4">&larr; Volver al inicio</a>

        <div class="card shadow-lg border-0 mb-5 overflow-hidden">
            <div class="row g-0">
                <div class="col-md-6 bg-white d-flex align-items-center justify-content-center p-4">
                    <img src="{{ asset('img/' . $product->image) }}" class="img-fluid rounded" alt="{{ $product->name }}" style="max-height: 400px; object-fit: contain;">
                </div>
                
                <div class="col-md-6 bg-light">
                    <div class="card-body p-5 h-100 d-flex flex-column justify-content-center">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h1 class="display-5 fw-bold mb-2">{{ $product->name }}</h1>
                                <p class="text-muted mb-3">Ref: {{ $product->sku }}</p>
                            </div>
                            <div class="text-end">
                                <div class="fs-4 text-warning fw-bold" id="avg-rating-display">
                                    {{ $product->rating }} ⭐
                                </div>
                                <small class="text-muted" id="total-reviews-display">
                                    {{ $product->reviews_count }} opiniones
                                </small>
                            </div>
                        </div>
                        
                        <p class="lead mb-4">{{ $product->description }}</p>
                        
                        <h2 class="text-primary fw-bold mb-4">{{ $product->price }} €</h2>

                        <div class="d-flex align-items-center gap-3 mb-4">
                            <button id="btn-like" class="btn btn-outline-danger btn-lg rounded-pill px-4" onclick="toggleLike()">
                                <span id="heart-icon">🤍</span> 
                                <span id="likes-count">0</span> Likes
                            </button>
                            <button class="btn btn-primary btn-lg px-5 rounded-pill">Añadir 🛒</button>
                        </div>

                        @guest
                            <div class="alert alert-info small">
                                <a href="{{ route('login') }}">Inicia sesión</a> para comprar o valorar.
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="bg-white p-5 rounded shadow-sm">
                    
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                        <h3 class="fw-bold m-0">💬 Opiniones de Clientes</h3>
                        <div class="text-end">
                            <h2 class="mb-0 text-warning" id="big-rating">0.0</h2>
                            <div id="stars-container" class="text-warning small">⭐⭐⭐⭐⭐</div>
                        </div>
                    </div>

                    <div id="comments-list" class="mb-5">
                        <p class="text-center text-muted">Cargando comentarios...</p>
                    </div>

                    @auth
                        <div class="card bg-light border-0 p-4 rounded-3">
                            <h5 class="fw-bold mb-3">✍️ ¡Danos tu opinión!</h5>
                            <form id="comment-form">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-uppercase">Puntuación</label>
                                    <div class="rating-css">
                                        <select id="rating" class="form-select" required>
                                            <option value="5">⭐⭐⭐⭐⭐ (5) Excelente</option>
                                            <option value="4">⭐⭐⭐⭐ (4) Muy bueno</option>
                                            <option value="3">⭐⭐⭐ (3) Normal</option>
                                            <option value="2">⭐⭐ (2) Regular</option>
                                            <option value="1">⭐ (1) Malo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-uppercase">Comentario</label>
                                    <textarea id="comment-text" class="form-control" rows="3" required placeholder="¿Qué te ha parecido el producto?"></textarea>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-dark px-4 fw-bold">PUBLICAR</button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="text-center py-4 bg-light rounded">
                            <p class="text-muted mb-2">¿Ya tienes este producto?</p>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Inicia sesión para opinar</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
    <br>
</main>

<script>
    const productId = {{ $product->id }};
    const isUserLoggedIn = @json(Auth::check());
    
    // Referencias DOM
    const btnLike = document.getElementById('btn-like');
    const heartIcon = document.getElementById('heart-icon');
    const likesCount = document.getElementById('likes-count');
    const commentsList = document.getElementById('comments-list');
    const commentForm = document.getElementById('comment-form');
    
    // Referencias para la media
    const bigRating = document.getElementById('big-rating');
    const starsContainer = document.getElementById('stars-container');
    const avgRatingDisplay = document.getElementById('avg-rating-display');
    const totalReviewsDisplay = document.getElementById('total-reviews-display');

    document.addEventListener('DOMContentLoaded', () => {
        loadLikeStatus();
        loadCommentsAndCalculateAverage(); // <--- Nueva función potente
    });

    // --- LÓGICA DE COMENTARIOS Y MEDIA ---
    async function loadCommentsAndCalculateAverage() {
        try {
            const response = await fetch(`/api/products/${productId}/comments`);
            const comments = await response.json();

            commentsList.innerHTML = '';
            
            // 1. Calcular Media en Cliente (JS)
            let totalRating = 0;
            if (comments.length > 0) {
                // Sumamos todas las notas
                comments.forEach(c => totalRating += c.rating);
                // Calculamos media
                const average = (totalRating / comments.length).toFixed(1);
                
                // Actualizamos la UI
                updateRatingUI(average, comments.length);
            } else {
                updateRatingUI(0, 0);
                commentsList.innerHTML = '<div class="alert alert-light text-center">Todavía no hay valoraciones. ¡Sé el primero!</div>';
                return;
            }

            // 2. Pintar lista de comentarios
            comments.forEach(comment => {
                const stars = '⭐'.repeat(comment.rating);
                const date = new Date(comment.created_at).toLocaleDateString();
                
                commentsList.innerHTML += `
                    <div class="d-flex mb-4 pb-3 border-bottom">
                        <div class="flex-shrink-0">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-weight:bold;">
                                ${comment.user.name.charAt(0).toUpperCase()}
                            </div>
                        </div>
                        <div class="ms-3 w-100">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mb-0 fw-bold">${comment.user.name} ${comment.user.surname || ''}</h6>
                                <span class="text-muted small">${date}</span>
                            </div>
                            <div class="text-warning small mb-2">${stars}</div>
                            <p class="mb-0 text-secondary">${comment.text}</p>
                        </div>
                    </div>
                `;
            });

        } catch (e) { console.error("Error comments", e); }
    }

    // Función auxiliar para pintar las estrellas de la media
    function updateRatingUI(average, count) {
        // Actualizar números
        if(bigRating) bigRating.innerText = average;
        if(avgRatingDisplay) avgRatingDisplay.innerText = average + ' ⭐';
        if(totalReviewsDisplay) totalReviewsDisplay.innerText = count + ' opiniones';

        // Pintar estrellas visuales según la media
        let starsHtml = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= Math.round(average)) {
                starsHtml += '⭐'; // Llena
            } else {
                starsHtml += '<span class="text-muted opacity-25">⭐</span>'; // Vacía (gris)
            }
        }
        if(starsContainer) starsContainer.innerHTML = starsHtml;
    }

    // --- PUBLICAR COMENTARIO ---
    if (commentForm) {
        commentForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const text = document.getElementById('comment-text').value;
            const rating = document.getElementById('rating').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch(`/api/products/${productId}/comments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ text, rating })
                });

                if (response.ok) {
                    document.getElementById('comment-text').value = '';
                    // Recargar todo (incluida la nueva media)
                    loadCommentsAndCalculateAverage(); 
                } else {
                    alert('Error al publicar comentario');
                }
            } catch (e) { console.error("Error posting", e); }
        });
    }

    // --- LIKES (Igual que antes) ---
    async function loadLikeStatus() {
        try {
            const response = await fetch(`/api/products/${productId}/like`, { headers: { 'Accept': 'application/json' } });
            const data = await response.json();
            updateLikeUI(data.is_liked, data.likes_count);
        } catch (e) {}
    }

    async function toggleLike() {
        if (!isUserLoggedIn) {
            window.location.href = "{{ route('login') }}";
            return;
        }
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const response = await fetch(`/api/products/${productId}/like`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            if(response.ok) {
                const data = await response.json();
                updateLikeUI(data.status === 'liked', data.likes_count);
            }
        } catch (e) {}
    }

    function updateLikeUI(isLiked, count) {
        likesCount.textContent = count;
        if (isLiked) {
            heartIcon.textContent = '❤️';
            btnLike.classList.remove('btn-outline-danger');
            btnLike.classList.add('btn-danger');
        } else {
            heartIcon.textContent = '🤍';
            btnLike.classList.add('btn-outline-danger');
            btnLike.classList.remove('btn-danger');
        }
    }
</script>
@endsection