<template>
    <MainLayout>
      <div class="contenido-principal container py-5">
        
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h2 class="fw-bold m-0">🛒 Tu Carrito de Compra</h2>
            <router-link :to="{ name: 'home' }" class="btn btn-outline-secondary">
                Volver al Inicio
            </router-link>
        </div>
  
        <div v-if="cartStore.cart.length > 0" class="row g-4">
          <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
              <div class="card-header bg-white py-3">
                <h5 class="m-0 fw-bold text-muted">Productos ({{ cartStore.itemCount }})</h5>
              </div>
              <div class="card-body p-0">
                <div 
                  v-for="item in cartStore.cart" 
                  :key="item.id" 
                  class="d-flex align-items-center p-3 border-bottom position-relative hover-bg-light"
                >
                  <div class="flex-shrink-0">
                    <img 
                      :src="getImageUrl(item.image)" 
                      :alt="item.name" 
                      @error="handleImageError"
                      class="rounded-3 border" 
                      style="width: 100px; height: 100px; object-fit: contain; background: #fff;"
                    >
                  </div>
                  
                  <div class="flex-grow-1 ms-3">
                    <h5 class="fw-bold mb-1">{{ item.name }}</h5>
                    <p class="text-muted small mb-2">Precio unidad: {{ formatCurrency(item.price) }}</p>
                    
                    <div class="d-flex align-items-center">
                      <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                        Cantidad: {{ item.quantity }}
                      </span>
                    </div>
                  </div>
  
                  <div class="text-end ms-3">
                    <h4 class="text-primary fw-bold mb-2">
                      {{ formatCurrency(item.price * item.quantity) }}
                    </h4>
                    <button 
                      @click="removeItem(item.id)" 
                      class="btn btn-link text-danger text-decoration-none small fw-bold hover-underline p-0"
                    >
                      Eliminar 🗑️
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-lg-4">
            <div class="card border-0 shadow rounded-4 sticky-top" style="top: 100px; z-index: 1;">
              <div class="card-body p-4">
                <h4 class="fw-bold mb-4">Resumen</h4>
                
                <div class="d-flex justify-content-between mb-2">
                  <span class="text-muted">Subtotal</span>
                  <span class="fw-bold">{{ formatCurrency(cartStore.totalPrice) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                  <span class="text-muted">Envío</span>
                  <span class="text-success fw-bold">Gratis</span>
                </div>
                
                <hr class="my-4">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                  <span class="fs-5 fw-bold">Total a Pagar</span>
                  <span class="fs-2 fw-bold text-primary">{{ formatCurrency(cartStore.totalPrice) }}</span>
                </div>
                
                <div class="d-grid gap-2">
                  <router-link :to="{ name: 'checkout' }" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm">
                    Tramitar Pedido →
                  </router-link>
                  <router-link :to="{ name: 'products' }" class="btn btn-outline-secondary rounded-pill">
                    Seguir Comprando
                  </router-link>
                </div>
                
                <div class="mt-4 text-center text-muted small">
                  🔒 Pago 100% Seguro garantizado
                </div>
              </div>
            </div>
          </div>
        </div>
  
        <div v-else class="text-center py-5">
          <div class="mb-4 display-1 text-muted opacity-25">🛒</div>
          <h3 class="fw-bold">Tu carrito está vacío</h3>
          <p class="text-muted mb-4">¡Añade productos increíbles a tu colección!</p>
          <router-link :to="{ name: 'products' }" class="btn btn-primary btn-lg rounded-pill px-5">
            Ir al Catálogo
          </router-link>
        </div>
      </div>
    </MainLayout>
  </template>
  
  <script setup>
  import { useCartStore } from '../stores/cart';
  import MainLayout from '../layouts/MainLayout.vue';
  
  const cartStore = useCartStore();
  
  // Función auxiliar para imágenes
  const getImageUrl = (imagePath) => {
      if (!imagePath) return '/img/marcaDeAgua.png';
      if (imagePath.startsWith('http')) return imagePath;
      return `http://localhost/img/${imagePath}`;
  };
  
  const handleImageError = (e) => e.target.src = '/img/marcaDeAgua.png';
  
  const formatCurrency = (value) => {
      return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
  };
  
  const removeItem = (id) => {
      if(confirm('¿Estás seguro de eliminar este producto?')) {
          cartStore.removeFromCart(id);
      }
  };
  </script>
  
  <style scoped>
      .hover-bg-light:hover { background-color: #f8f9fa; transition: 0.3s; }
      .hover-underline:hover { text-decoration: underline !important; }
  </style>