<template>
  <MainLayout>
    <main class="contenido-principal" style="padding-top: 50px; min-height: 100vh;">
      
      <div class="container">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold">Nuestro Catálogo</h1>
            <p class="lead text-muted">Explora todos nuestros productos e impresoras</p>
        </div>

        <div v-if="loading" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>

        <div v-else class="contenedor-productos">
            
            <div 
              v-for="product in products" 
              :key="product.id" 
              class="tarjeta-producto" 
              :class="{ 'agotado': product.stock <= 0 }"
              style="position: relative; overflow: hidden;"
            >

                <div v-if="product.stock <= 0" class="overlay-agotado">
                    <span class="badge-agotado">AGOTADO</span>
                </div>

                <img
                    :src="getImagePath(product.image)"
                    :alt="product.name"
                    @error="handleImageError"
                    :style="product.stock <= 0 ? 'filter: grayscale(100%); opacity: 0.5;' : ''"
                >

                <h3>{{ product.name }}</h3>

                <p class="producto-descripcion">
                    {{ truncate(product.description, 100) }}
                </p>

                <div class="d-flex justify-content-between align-items-center mb-2" style="width: 100%; padding: 0 10px;">
                    <span class="producto-precio">
                        {{ formatPrice(product.price) }}
                    </span>
                    
                    <small v-if="product.stock > 0 && product.stock <= 5" class="text-danger fw-bold">
                        ¡Quedan {{ product.stock }}!
                    </small>
                </div>

                <router-link 
                    :to="`/products/${product.id}`" 
                    class="btn w-100 mt-2"
                    :class="product.stock <= 0 ? 'btn-secondary disabled' : 'btn-primary'"
                    :style="{ pointerEvents: product.stock <= 0 ? 'none' : 'auto' }"
                >
                    {{ product.stock <= 0 ? 'Sin Stock' : 'Ver Detalles y Opinar' }}
                </router-link>

            </div>
        </div>
      </div>
      <br>

    </main>
  </MainLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import MainLayout from '../layouts/MainLayout.vue';
import axios from '../api/axios'; 

const products = ref([]);
const loading = ref(true);

// --- Cargar Productos desde la API Laravel ---
const fetchProducts = async () => {
  try {
    const response = await axios.get('/api/products');
    products.value = response.data;
  } catch (error) {
    console.error("Error al cargar el catálogo:", error);
  } finally {
    loading.value = false;
  }
};

// --- Helpers para formato ---
const getImagePath = (imageName) => {
    if (!imageName) return '/img/marcaDeAgua.png';
    // Si la imagen ya viene con http (ej: lorempixel), la dejamos igual
    if (imageName.startsWith('http')) return imageName;
    // Si no, asumimos que está en la carpeta publica /img/
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

// --- Al iniciar el componente ---
onMounted(() => {
    fetchProducts();
});
</script>

<style scoped>
/* Estilos específicos para replicar el overlay de Agotado de Blade */
.overlay-agotado {
    position: absolute; 
    top: 0; 
    left: 0; 
    width: 100%; 
    height: 100%; 
    background: rgba(255,255,255,0.6); 
    z-index: 5; 
    display: flex; 
    align-items: center; 
    justify-content: center;
}

.badge-agotado {
    background: #dc3545; 
    color: white; 
    padding: 10px 20px; 
    font-weight: bold; 
    transform: rotate(-15deg); 
    font-size: 1.2rem; 
    border-radius: 5px; 
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

/* Ajuste del botón para que coincida con el estilo legacy */
.btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
    text-decoration: none;
    text-align: center;
}
.btn-primary:hover {
    background-color: #0b5ed7;
}

/* Aseguramos que la rejilla herede los estilos globales, 
   pero si no cargan, forzamos un grid básico */
.contenedor-productos {
    display: grid;
    gap: 28px;
    width: 100%;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    padding-bottom: 40px;
}
</style>