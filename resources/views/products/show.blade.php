@extends('layouts.products')

@section('title', $product->name)

@section('content')
<main class="contenido-principal" style="min-height: 100vh;">
    
    <div class="container">
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <strong>🚫 Error:</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <a href="{{ route('home') }}" class="btn btn-outline-secondary mb-4 mt-4">&larr; Volver</a>

        <div class="card shadow-lg border-0 mb-5 overflow-hidden">
            <div class="row g-0">
                <div class="col-md-6 bg-white d-flex align-items-center justify-content-center p-4 position-relative">
                    
                    {{-- ETIQUETA DE AGOTADO EN LA IMAGEN --}}
                    @if($product->stock <= 0)
                        <div class="position-absolute w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(255,255,255,0.7); z-index: 10;">
                            <span class="badge bg-danger fs-1 shadow rotate-badge" style="transform: rotate(-15deg); padding: 20px 40px; border: 4px solid white;">AGOTADO</span>
                        </div>
                    @endif

                    <img src="{{ asset('img/' . $product->image) }}" class="img-fluid rounded" alt="{{ $product->name }}" style="max-height: 400px; object-fit: contain;">
                </div>
                
                <div class="col-md-6 bg-light">
                    <div class="card-body p-5 h-100 d-flex flex-column justify-content-center">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h1 class="display-5 fw-bold mb-2">{{ $product->name }}</h1>
                                <p class="text-muted mb-3">Ref: {{ $product->sku ?? 'N/A' }}</p>
                            </div>
                            <div class="text-end">
                                <div class="fs-4 text-warning fw-bold" id="avg-rating-display">
                                    {{ $product->rating ?? 0 }} ⭐
                                </div>
                                <small class="text-muted" id="total-reviews-display">
                                    {{ $product->reviews_count ?? 0 }} opiniones
                                </small>
                            </div>
                        </div>
                        
                        <p class="lead mb-4">{{ $product->description }}</p>
                        
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <h2 class="text-primary fw-bold mb-0">{{ $product->price }} €</h2>
                            
                            {{-- INFORMACIÓN DE STOCK --}}
                            @if($product->stock > 5)
                                <span class="badge bg-success">En Stock ({{ $product->stock }})</span>
                            @elseif($product->stock > 0)
                                <span class="badge bg-warning text-dark">¡Últimas unidades! ({{ $product->stock }})</span>
                            @else
                                <span class="badge bg-secondary">Sin Stock</span>
                            @endif
                        </div>

                        <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
                            <button id="btn-like" class="btn btn-outline-danger btn-lg rounded-pill px-4" onclick="toggleLike()">
                                <span id="heart-icon">🤍</span> 
                                <span id="likes-count">0</span> Likes
                            </button>
                            
                            {{-- BOTÓN DE COMPRA: LOGICA DE ESTADO --}}
                            @if($product->stock > 0)
                                <a href="{{ route('cart.add', $product->id) }}" class="btn btn-success btn-lg px-5 rounded-pill shadow">
                                    Añadir al Carrito 🛒
                                </a>
                            @else
                                <button class="btn btn-secondary btn-lg px-5 rounded-pill shadow" disabled style="cursor: not-allowed; opacity: 0.6;">
                                    🚫 AGOTADO
                                </button>
                            @endif

                            @auth
                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-lg rounded-pill shadow fw-bold border border-dark">
                                        ✏️ Editar
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECCIÓN DE COMENTARIOS (Sin cambios) --}}
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="bg-white p-5 rounded shadow-sm">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                        <h3 class="fw-bold m-0">💬 Opiniones</h3>
                        <div class="text-end">
                            <h2 class="mb-0 text-warning" id="big-rating">0.0</h2>
                            <div id="stars-container" class="text-warning small">⭐⭐⭐⭐⭐</div>
                        </div>
                    </div>

                    <div id="comments-list" class="mb-5">
                        <p class="text-center text-muted">Cargando...</p>
                    </div>

                    @auth
                        <div class="card bg-light border-0 p-4 rounded-3">
                            <h5 class="fw-bold mb-3">✍️ Deja tu valoración</h5>
                            <form id="comment-form">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Puntuación</label>
                                    <select id="rating" class="form-select w-auto" required>
                                        <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                                        <option value="4">⭐⭐⭐⭐ (4)</option>
                                        <option value="3">⭐⭐⭐ (3)</option>
                                        <option value="2">⭐⭐ (2)</option>
                                        <option value="1">⭐ (1)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <textarea id="comment-text" class="form-control" rows="3" required placeholder="Escribe aquí..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-dark fw-bold">PUBLICAR</button>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <a href="{{ route('login') }}">Inicia sesión</a> para comentar.
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
    <br>
</main>

<script>
    // Variables para el JS
    const productId = {{ $product->id }};
    const isUserLoggedIn = @json(Auth::check());
    const currentUserId = @json(Auth::id());
    const currentUserRole = @json(Auth::user()->role ?? 'user');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Elementos del DOM
    const commentsList = document.getElementById('comments-list');
    const commentForm = document.getElementById('comment-form');
    const bigRating = document.getElementById('big-rating');
    const starsContainer = document.getElementById('stars-container');
    const avgRatingDisplay = document.getElementById('avg-rating-display');
    const totalReviewsDisplay = document.getElementById('total-reviews-display');
    const btnLike = document.getElementById('btn-like');
    const heartIcon = document.getElementById('heart-icon');
    const likesCount = document.getElementById('likes-count');

    document.addEventListener('DOMContentLoaded', () => {
        loadLikeStatus();
        loadCommentsAndCalculateAverage();
    });

    // --- CARGAR COMENTARIOS ---
    async function loadCommentsAndCalculateAverage() {
        try {
            const response = await fetch(`/api/products/${productId}/comments`);
            const comments = await response.json();
            commentsList.innerHTML = '';
            
            let totalRating = 0;
            if (comments.length > 0) {
                comments.forEach(c => totalRating += c.rating);
                const average = (totalRating / comments.length).toFixed(1);
                updateRatingUI(average, comments.length);
            } else {
                updateRatingUI(0, 0);
                commentsList.innerHTML = '<div class="text-center text-muted">Aún no hay comentarios.</div>';
                return;
            }

            comments.forEach(comment => {
                const isOwner = (currentUserId === comment.user_id);
                const isAdmin = (currentUserRole === 'admin');
                const stars = '⭐'.repeat(comment.rating);
                const date = new Date(comment.created_at).toLocaleDateString();
                let actionButtons = '';
                
                if (isOwner) {
                    actionButtons += `<button class="btn btn-sm btn-link text-primary p-0 me-3 text-decoration-none" onclick="enableEditMode(${comment.id}, '${comment.text.replace(/'/g, "\\'")}', ${comment.rating})">✏️ Editar</button>`;
                }
                if (isOwner || isAdmin) {
                    actionButtons += `<button class="btn btn-sm btn-link text-danger p-0 text-decoration-none" onclick="deleteComment(${comment.id})">🗑️ Borrar</button>`;
                }

                const html = `
                    <div class="d-flex mb-4 pb-3 border-bottom position-relative" id="comment-card-${comment.id}">
                        <div class="flex-shrink-0">
                            <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; font-weight:bold;">${comment.user.name.charAt(0).toUpperCase()}</div>
                        </div>
                        <div class="ms-3 w-100">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold">${comment.user.name} ${comment.user.surname || ''} ${comment.user.role === 'admin' ? '<span class="badge bg-danger ms-1">ADMIN</span>' : ''}</h6>
                                <span class="text-muted small">${date}</span>
                            </div>
                            
                            <div id="view-mode-${comment.id}">
                                <div class="text-warning small mb-1">${stars}</div>
                                <p class="mb-1 text-secondary">${comment.text}</p>
                                <div class="mt-2">${actionButtons}</div>
                            </div>

                            <div id="edit-mode-${comment.id}" style="display:none;" class="mt-3 p-3 bg-light rounded">
                                <select id="edit-rating-${comment.id}" class="form-select form-select-sm w-auto mb-2"><option value="5">5</option><option value="4">4</option><option value="3">3</option><option value="2">2</option><option value="1">1</option></select>
                                <textarea id="edit-text-${comment.id}" class="form-control mb-2" rows="2"></textarea>
                                <div class="text-end"><button class="btn btn-sm btn-secondary me-1" onclick="cancelEdit(${comment.id})">Cancelar</button><button class="btn btn-sm btn-success" onclick="updateComment(${comment.id})">Guardar</button></div>
                            </div>
                        </div>
                    </div>`;
                commentsList.innerHTML += html;
            });
        } catch (e) { console.error(e); }
    }

    // --- FUNCIONES DE EDICIÓN COMENTARIOS ---
    window.enableEditMode = function(id, text, rating) {
        document.getElementById(`view-mode-${id}`).style.display = 'none';
        document.getElementById(`edit-mode-${id}`).style.display = 'block';
        document.getElementById(`edit-text-${id}`).value = text;
        document.getElementById(`edit-rating-${id}`).value = rating;
    }
    window.cancelEdit = function(id) {
        document.getElementById(`view-mode-${id}`).style.display = 'block';
        document.getElementById(`edit-mode-${id}`).style.display = 'none';
    }
    window.updateComment = async function(id) {
        const text = document.getElementById(`edit-text-${id}`).value;
        const rating = document.getElementById(`edit-rating-${id}`).value;
        try {
            const response = await fetch(`/api/comments/${id}`, { 
                method: 'PUT', 
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }, 
                body: JSON.stringify({ text, rating }) 
            });
            if (response.ok) loadCommentsAndCalculateAverage();
        } catch (e) {}
    }
    window.deleteComment = async function(id) {
        if (!confirm('¿Borrar comentario?')) return;
        try {
            const response = await fetch(`/api/comments/${id}`, { 
                method: 'DELETE', 
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } 
            });
            if (response.ok) loadCommentsAndCalculateAverage();
        } catch (e) {}
    }

    // --- ENVIAR NUEVO COMENTARIO ---
    if (commentForm) {
        commentForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const text = document.getElementById('comment-text').value;
            const rating = document.getElementById('rating').value;
            try {
                const response = await fetch(`/api/products/${productId}/comments`, { 
                    method: 'POST', 
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }, 
                    body: JSON.stringify({ text, rating }) 
                });
                if (response.ok) { 
                    document.getElementById('comment-text').value = ''; 
                    loadCommentsAndCalculateAverage(); 
                }
            } catch (e) {}
        });
    }

    // --- UTILIDADES UI ---
    function updateRatingUI(average, count) {
        if(bigRating) bigRating.innerText = average;
        if(avgRatingDisplay) avgRatingDisplay.innerText = average + ' ⭐';
        if(totalReviewsDisplay) totalReviewsDisplay.innerText = count + ' opiniones';
        let starsHtml = '';
        for (let i = 1; i <= 5; i++) starsHtml += (i <= Math.round(average)) ? '⭐' : '<span class="text-muted opacity-25">⭐</span>';
        if(starsContainer) starsContainer.innerHTML = starsHtml;
    }

    // --- LIKES ---
    async function loadLikeStatus() {
        try {
            const response = await fetch(`/api/products/${productId}/like`, { headers: { 'Accept': 'application/json' } });
            const data = await response.json();
            updateLikeUI(data.is_liked, data.likes_count);
        } catch (e) {}
    }
    window.toggleLike = async function() {
        if (!isUserLoggedIn) { window.location.href = "{{ route('login') }}"; return; }
        try {
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
        if (!likesCount) return;
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