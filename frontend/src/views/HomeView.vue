<template>
  <MainLayout>
    <div class="contenido-principal">

      <a href="#destacados" class="enlace-banner" style="display:block;">
        <div class="banner">
          <div class="banner-texto">
            ¡Oferta especial de Navidad! 🦌🎅 Solo por tiempo limitado.
          </div>
        </div>
      </a>

      <section class="hero" style="background: url('/img/fondoPrincipio.jpg') no-repeat center center/cover;">
        <div class="container h-100">
          <div class="row h-100 hero-fila">
            <div class="col-12 hero-contenido">
              <h1>Bienvenido/a a Print<span class="resaltado">Hub</span></h1>
              <p>Innovación y calidad en cada producto</p>
              <a href="#destacados" class="flecha">&#x25BC;</a>
            </div>
          </div>
        </div>
      </section>

      <section v-if="ofertaDia" class="py-5 bg-dark text-white" style="background: linear-gradient(135deg, #1a1a1a, #2c3e50);">
        <div class="container">
          <div class="row align-items-center bg-white rounded-4 shadow overflow-hidden text-dark p-0">

            <div class="col-md-6 p-0 position-relative" style="background: #f8f9fa; min-height: 400px; display: flex; align-items: center; justify-content: center;">
              <div class="position-absolute top-0 start-0 bg-danger text-white fw-bold px-4 py-2 shadow" style="font-size: 1.5rem; z-index: 10; border-bottom-right-radius: 10px;">
                🔥 -50% HOY
              </div>
              <img :src="getImagePath(ofertaDia.image)" :alt="ofertaDia.name" class="img-fluid" style="max-height: 350px; object-fit: contain;">
            </div>

            <div class="col-md-6 p-5 text-center text-md-start">
              <h4 class="text-danger fw-bold text-uppercase mb-2">⚡ Oferta Flash Exclusiva</h4>
              <h2 class="display-4 fw-bold mb-3">{{ ofertaDia.name }}</h2>
              <p class="lead text-muted mb-4">{{ truncate(ofertaDia.description, 120) }}</p>

              <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-3 mb-4">
                <div class="text-decoration-line-through text-muted fs-3">{{ formatPrice(ofertaDia.price) }}</div>
                <div class="text-danger fw-bold display-3 animate-price">{{ formatPrice(ofertaDia.price / 2) }}</div>
              </div>

              <div class="d-flex flex-column flex-md-row gap-3 justify-content-center justify-content-md-start">
                <router-link :to="`/productes/${ofertaDia.id}`" class="btn btn-outline-dark btn-lg rounded-pill px-4">
                  Ver Detalles
                </router-link>
                <button class="btn btn-danger btn-lg rounded-pill px-5 shadow fw-bold animate-pulse">
                  ¡COMPRAR AHORA! 🛒
                </button>
              </div>

              <div class="mt-4 pt-3 border-top small text-muted">
                * Oferta válida solo durante el día de hoy. Stock limitado: <strong>{{ ofertaDia.stock }} unidades</strong>.
              </div>
            </div>

          </div>
        </div>
      </section>

      <section id="destacados" class="productos-destacados pt-5">
        <div class="container">
          <h1>Productos Destacados</h1>

          <div v-if="loading" class="text-center py-5">Cargando productos...</div>

          <div v-else class="contenedor-productos">
            <div v-for="product in destacados" :key="product.id" class="tarjeta-producto" :class="{ 'agotado': product.stock <= 0 }">

              <div v-if="product.stock <= 0" class="overlay-agotado">
                <span class="badge-agotado">AGOTADO</span>
              </div>

              <img :src="getImagePath(product.image)" :alt="product.name" @error="handleImageError">

              <h3>{{ product.name }}</h3>
              <p class="producto-descripcion">{{ truncate(product.description, 80) }}</p>

              <div class="mb-2">
                <span class="producto-precio">{{ formatPrice(product.price) }}</span>
              </div>

              <router-link :to="`/productes/${product.id}`" class="btn w-100 mt-2" :class="product.stock <= 0 ? 'btn-secondary disabled' : 'boton'">
                Ver Detalles y Opinar
              </router-link>
            </div>
          </div>
        </div>
      </section>

      <!-- CONTENEDOR CHATBOT -->
      <div id="n8n-chat"></div>

    </div>
  </MainLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import MainLayout from '../layouts/MainLayout.vue';
import axios from '../api/axios';

const products = ref([]);
const loading = ref(true);

const fetchProducts = async () => {
  try {
    const response = await axios.get('/api/products');
    products.value = response.data;
  } catch (error) {
    console.error("Error fetching products:", error);
  } finally {
    loading.value = false;
  }
};

const impresoras = computed(() => {
  return products.value.filter(p => p.category && p.category.toLowerCase() === 'impresoras');
});

const destacados = computed(() => {
  return products.value.filter(p => !p.category || p.category.toLowerCase() !== 'impresoras');
});

const ofertaDia = computed(() => {
  if (products.value.length === 0) return null;
  return products.value.find(p => p.id === 1) || products.value[0];
});

const getImagePath = (imageName) => {
  if (!imageName) return '/img/marcaDeAgua.png';
  if (imageName.startsWith('http')) return imageName;
  return `/img/${imageName}`;
};

const handleImageError = (e) => {
  e.target.src = '/img/marcaDeAgua.png';
};

const formatPrice = (price) => {
  return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(price);
};

const truncate = (text, length) => {
  if (!text) return '';
  return text.length > length ? text.substring(0, length) + '...' : text;
};

onMounted(() => {
  fetchProducts();

  // Cargar CSS del chat
  const link = document.createElement('link');
  link.href = "https://cdn.jsdelivr.net/npm/@n8n/chat/dist/style.css";
  link.rel = "stylesheet";
  document.head.appendChild(link);

  // Cargar script del chat
  const script = document.createElement('script');
  script.type = "module";
  script.innerHTML = `
    import { createChat } from 'https://cdn.jsdelivr.net/npm/@n8n/chat/dist/chat.bundle.es.js';
    createChat({
      webhookUrl: 'http://172.16.221.74:5678/webhook/2f4e8def-1604-43d9-a0c4-5677de10f699/chat'
    });
  `;
  document.body.appendChild(script);
});
</script>

<style>
#n8n-chat {
  position: fixed;
  bottom: 16px;
  right: 16px;
  z-index: 999;
}
</style>
