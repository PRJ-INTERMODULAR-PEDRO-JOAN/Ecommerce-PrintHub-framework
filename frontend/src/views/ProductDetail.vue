<template>
    <MainLayout>
      <main class="contenido-principal" style="padding-top: 50px; min-height: 100vh;">
        <div class="container">
          
          <button @click="$router.back()" class="btn btn-outline-secondary mb-4">
            &larr; Volver al Catálogo
          </button>
  
          <div v-if="loading" class="text-center py-5">
              <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Cargando...</span>
              </div>
          </div>
  
          <div v-else-if="product" class="card shadow-lg border-0 mb-5 overflow-hidden">
              <div class="row g-0">
                  
                  <div class="col-md-6 bg-white d-flex align-items-center justify-content-center p-4 position-relative">
                      <div v-if="product.stock <= 0" class="overlay-agotado-detalle">
                          <span class="badge-agotado-detalle">AGOTADO</span>
                      </div>
  
                      <img 
                          :src="getImagePath(product.image)" 
                          class="img-fluid rounded" 
                          :alt="product.name" 
                          @error="handleImageError"
                          style="max-height: 400px; object-fit: contain;"
                          :style="product.stock <= 0 ? 'filter: grayscale(100%); opacity: 0.5;' : ''"
                      >
                  </div>
                  
                  <div class="col-md-6 bg-light">
                      <div class="card-body p-5 h-100 d-flex flex-column justify-content-center">
                          <div class="d-flex justify-content-between align-items-start">
                              <h1 class="display-5 fw-bold mb-2">{{ product.name }}</h1>
                              <div class="text-end">
                                  <div class="fs-4 text-warning fw-bold">{{ averageRating }} ⭐</div>
                                  <small class="text-muted">{{ comments.length }} opiniones</small>
                              </div>
                          </div>
                          
                          <p class="lead mb-4 text-muted">{{ product.description }}</p>
                          
                          <div class="d-flex align-items-center gap-3 mb-4">
                              <h2 class="text-primary fw-bold mb-0">{{ formatPrice(product.price) }}</h2>
                              
                              <span v-if="product.stock > 5" class="badge bg-success">En Stock ({{ product.stock }})</span>
                              <span v-else-if="product.stock > 0" class="badge bg-warning text-dark">¡Últimas unidades! ({{ product.stock }})</span>
                              <span v-else class="badge bg-secondary">Sin Stock</span>
                          </div>
  
                          <div class="d-flex flex-wrap align-items-center gap-3 mb-4 position-relative">
                              <button
                                  v-if="auth.user"
                                  @click="toggleLike" 
                                  class="btn btn-lg rounded-pill px-4 transition-btn"
                                  :class="isLiked ? 'btn-danger' : 'btn-outline-danger'"
                              >
                                  <span v-if="isLiked">❤️</span><span v-else>🤍</span> 
                                  {{ likesCount }} Likes
                              </button>
                              
                              <button v-else                                   
                                  class="btn btn-lg rounded-pill px-4 transition-btn btn-danger"
                                  @click="$router.push('/login')"
                              >
                                Login para Likes
                              </button>
  
                              <button 
                                  v-if="canAddToCart" 
                                  @click="handleAddToCart"
                                  class="btn btn-success btn-lg px-5 rounded-pill shadow transition-btn"
                              >
                                  Añadir al Carrito 🛒
                              </button>
  
                              <button 
                                  v-else-if="product.stock > 0 && !canAddToCart" 
                                  class="btn btn-warning btn-lg px-5 rounded-pill shadow text-dark fw-bold" 
                                  disabled
                                  title="No puedes añadir más unidades de las que hay disponibles"
                              >
                                  Máx. Stock Alcanzado ⚠️
                              </button>
  
                              <button v-else class="btn btn-secondary btn-lg px-5 rounded-pill shadow" disabled>
                                  🚫 AGOTADO
                              </button>
  
                              <transition name="fade">
                                  <span v-if="showAddedMessage" class="added-notification text-success fw-bold ms-2">
                                      ✅ ¡Añadido!
                                  </span>
                              </transition>
  
                              <router-link 
                                  v-if="auth.user && auth.user.role === 'admin'"
                                  :to="`/products/${product.id}/edit`" 
                                  class="btn btn-warning btn-lg rounded-pill shadow fw-bold border border-dark"
                              >
                                  ✏️ Editar
                              </router-link>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
  
          <div v-if="product" class="row justify-content-center mb-5">
              <div class="col-lg-8">
                  <div class="bg-white p-5 rounded shadow-sm">
                      <h3 class="fw-bold border-bottom pb-3 mb-4">💬 Opiniones</h3>
  
                      <div v-if="comments.length > 0" class="mb-5">
                          <div v-for="comment in comments" :key="comment.id" class="d-flex mb-4 pb-3 border-bottom position-relative">
                              <div class="flex-shrink-0">
                                  <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; font-weight:bold;">
                                      {{ comment.user.name.charAt(0).toUpperCase() }}
                                  </div>
                              </div>
                              <div class="ms-3 w-100">
                                  <div class="d-flex justify-content-between align-items-center">
                                      <h6 class="mb-0 fw-bold">
                                          {{ comment.user.name }} {{ comment.user.surname }}
                                          <span v-if="comment.user.role === 'admin'" class="badge bg-danger ms-1" style="font-size: 0.6em;">ADMIN</span>
                                      </h6>
                                      <small class="text-muted">{{ new Date(comment.created_at).toLocaleDateString() }}</small>
                                  </div>
                                  <div class="text-warning small mb-1">{{ '⭐'.repeat(comment.rating) }}</div>
                                  <p class="mb-1 text-secondary">{{ comment.text }}</p>
  
                                  <div v-if="auth.user && (auth.user.id === comment.user_id || auth.user.role === 'admin')" class="mt-2">
                                      <button @click="deleteComment(comment.id)" class="btn btn-sm btn-link text-danger p-0 text-decoration-none">🗑️ Borrar</button>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <p v-else class="text-center text-muted mb-5">Aún no hay comentarios.</p>
  
                      <div v-if="auth.user" class="card bg-light border-0 p-4 rounded-3">
                          <h5 class="fw-bold mb-3">✍️ Deja tu valoración</h5>
                          <form @submit.prevent="submitComment">
                              <select v-model="newComment.rating" class="form-select w-auto mb-3" required>
                                  <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                                  <option value="4">⭐⭐⭐⭐ (4)</option>
                                  <option value="3">⭐⭐⭐ (3)</option>
                                  <option value="2">⭐⭐ (2)</option>
                                  <option value="1">⭐ (1)</option>
                              </select>
                              <textarea v-model="newComment.text" class="form-control mb-3" rows="3" required placeholder="Escribe tu opinión..."></textarea>
                              <button type="submit" class="btn btn-dark fw-bold">PUBLICAR</button>
                          </form>
                      </div>
                      <div v-else class="alert alert-info text-center">
                          <router-link to="/login">Inicia sesión</router-link> para opinar.
                      </div>
                  </div>
              </div>
          </div>
  
        </div>
      </main>
    </MainLayout>
  </template>
  
  <script setup>
  import { ref, onMounted, computed } from 'vue';
  import { useRoute } from 'vue-router';
  import MainLayout from '../layouts/MainLayout.vue';
  import axios from '../api/axios';
  import { useAuthStore } from '../stores/auth';
  import { useCartStore } from '../stores/cart';
  
  const route = useRoute();
  const auth = useAuthStore();
  const cartStore = useCartStore();
  
  const product = ref(null);
  const comments = ref([]);
  const loading = ref(true);
  const isLiked = ref(false);
  const likesCount = ref(0);
  const showAddedMessage = ref(false);
  
  const newComment = ref({ rating: 5, text: '' });
  
  const averageRating = computed(() => {
      if (!comments.value.length) return 0;
      const sum = comments.value.reduce((acc, c) => acc + parseInt(c.rating), 0);
      return (sum / comments.value.length).toFixed(1);
  });
  
  // --- LÓGICA DE STOCK VS CARRITO ---
  const canAddToCart = computed(() => {
      if (!product.value) return false;
      
      // 1. Si el producto no tiene stock base
      if (product.value.stock <= 0) return false;
  
      // 2. Comprobar cuántos tenemos ya en el carrito
      const itemInCart = cartStore.cart.find(item => item.id === product.value.id);
      const quantityInCart = itemInCart ? itemInCart.quantity : 0;
  
      // 3. Permitir solo si (lo que tengo + 1) es menor o igual al stock
      return (quantityInCart + 1) <= product.value.stock;
  });
  
  onMounted(async () => {
      const id = route.params.id;
      loading.value = true;
      try {
          const [prodRes, commRes] = await Promise.all([
              axios.get(`/api/products/${id}`),
              axios.get(`/api/products/${id}/comments`)
          ]);
          
          product.value = prodRes.data;
          comments.value = commRes.data;
  
          if (auth.user) {
              try {
                  const likeRes = await axios.get(`/api/products/${id}/like`);
                  isLiked.value = likeRes.data.is_liked;
                  likesCount.value = likeRes.data.likes_count;
              } catch (e) {}
          } else {
              likesCount.value = prodRes.data.likes_count || 0;
          }
  
      } catch (error) {
          console.error("Error:", error);
      } finally {
          loading.value = false;
      }
  });
  
  const toggleLike = async () => {
      if (!auth.user) return alert("Debes iniciar sesión.");
      try {
          const res = await axios.post(`/api/products/${product.value.id}/like`);
          isLiked.value = res.data.status === 'liked';
          likesCount.value = res.data.likes_count;
      } catch (e) { console.error(e); }
  };
  
  const submitComment = async () => {
      try {
          await axios.post(`/api/products/${product.value.id}/comments`, newComment.value);
          const res = await axios.get(`/api/products/${product.value.id}/comments`);
          comments.value = res.data;
          newComment.value.text = '';
      } catch (e) { alert("Error al publicar."); }
  };
  
  const deleteComment = async (id) => {
      if(!confirm("¿Borrar?")) return;
      try {
          await axios.delete(`/api/comments/${id}`);
          const res = await axios.get(`/api/products/${product.value.id}/comments`);
          comments.value = res.data;
      } catch (e) { alert("Error al borrar."); }
  };
  
  const handleAddToCart = () => {
      if (canAddToCart.value) {
          cartStore.addToCart(product.value);
          showAddedMessage.value = true;
          setTimeout(() => { showAddedMessage.value = false; }, 2000);
      }
  };
  
  // --- PUERTO 80 ---
  const getImagePath = (img) => {
      if (!img) return '/img/marcaDeAgua.png';
      // Si la imagen ya es absoluta, devolverla.
      if (img.startsWith('http')) return img;
      // Si Laravel está en el puerto 80, no ponemos puerto (es el default)
      return `http://localhost/img/${img}`;
  };
  
  const handleImageError = (e) => e.target.src = '/img/marcaDeAgua.png';
  
  const formatPrice = (p) => {
      if (p === undefined || p === null) return '0,00 €';
      return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(p);
  };
  </script>
  
  <style scoped>
  .overlay-agotado-detalle {
      position: absolute; top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(255,255,255,0.7); z-index: 10;
      display: flex; align-items: center; justify-content: center;
  }
  .badge-agotado-detalle {
      background: #dc3545; color: white; padding: 20px 40px; font-weight: bold;
      transform: rotate(-15deg); font-size: 2rem; border: 4px solid white;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
  }
  .transition-btn { transition: transform 0.2s; }
  .transition-btn:active { transform: scale(0.95); }
  
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.5s ease;
  }
  
  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
  }
  
  .added-notification {
      background-color: #d1e7dd;
      color: #0f5132;
      padding: 0.5rem 1rem;
      border-radius: 20px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  }
  </style>