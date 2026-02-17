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
                <router-link :to="`/products/${ofertaDia.id}`" class="btn btn-outline-dark btn-lg rounded-pill px-4">
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
              <div v-else-if="ofertaDia && ofertaDia.id === product.id" class="badge-oferta">
                <span class="badge bg-danger shadow animate-pulse">🔥 -50%</span>
              </div>

              <img :src="getImagePath(product.image)" :alt="product.name" @error="handleImageError">

              <h3>{{ product.name }}</h3>
              <p class="producto-descripcion">{{ truncate(product.description, 80) }}</p>

              <div class="mb-2">
                <span v-if="ofertaDia && ofertaDia.id === product.id">
                  <span class="text-decoration-line-through text-muted small me-2">{{ formatPrice(product.price) }}</span>
                  <span class="producto-precio text-danger fw-bold">{{ formatPrice(product.price / 2) }}</span>
                </span>
                <span v-else class="producto-precio">{{ formatPrice(product.price) }}</span>
              </div>

              <router-link :to="`/products/${product.id}`" class="btn w-100 mt-2" :class="product.stock <= 0 ? 'btn-secondary disabled' : 'boton'">
                Ver Detalles y Opinar
              </router-link>
            </div>
          </div>
        </div>
      </section>

      <section id="impresoras" class="impresoras-section">
        <div class="container">
          <h1 class="impresoras-titulo">Nuestras Impresoras 3D</h1>
          
          <div v-if="loading" class="text-center">Cargando impresoras...</div>
          
          <div v-else class="contenedor-productos">
            <div v-for="impresora in impresoras" :key="impresora.id" class="tarjeta-producto" :class="{ 'agotado': impresora.stock <= 0 }">
              
               <div v-if="impresora.stock <= 0" class="overlay-agotado">
                <span class="badge-agotado">AGOTADO</span>
              </div>

              <img :src="getImagePath(impresora.image)" :alt="impresora.name" @error="handleImageError">

              <h3>{{ impresora.name }}</h3>
              <p class="producto-descripcion">{{ truncate(impresora.description, 80) }}</p>
              
              <div class="mb-2">
                  <span class="producto-precio">{{ formatPrice(impresora.price) }}</span>
              </div>

              <router-link :to="`/products/${impresora.id}`" class="btn w-100 mt-2" :class="impresora.stock <= 0 ? 'btn-secondary disabled' : 'boton'">
                Ver Detalles y Opinar
              </router-link>
            </div>
          </div>
        </div>
      </section>

      <section class="seccion-sobre-nosotros">
        <div class="container">
            <div class="row gy-4 sobre-nosotros-fila">
                <div class="col-12 col-md-12 col-lg-5 gif-sobre-nosotros">
                    <img src="/img/impresora.gif" alt="GIF PrintHub" class="img-fluid" />
                </div>
                <div class="col-12 col-md-12 col-lg-7 texto-sobre-nosotros">
                    <h2>Sobre Nosotros</h2>
                    <p>En PrintHub, nos especializamos en la creación de maquetas personalizadas utilizando tecnología de impresión 3D de última generación...</p>
                    <p>Ya sea que busques una figura de tu videojuego favorito, una maqueta arquitectónica o un modelo de automóvil...</p>
                </div>
            </div>
        </div>
      </section>

      <section class="video">
        <div class="container">
            <div class="video-wrapper">
                <h2 class="text-black">Descubre Más Sobre PrintHub</h2>
                <div class="video-contenedor">
                    <video autoplay loop muted playsinline class="img-fluid">
                        <source src="/img/Presentacion_Clientes_sin_con.mp4" type="video/mp4" />
                    </video>
                </div>
            </div>
        </div>
      </section>

      <section class="seccion-testimonios">
        <div class="container">
            <h2>Qué dicen nuestros clientes</h2>
            <div class="row g-4 contenedor-testimonios">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="tarjeta-testimonio h-100">
                        <p class="cita-testimonio">"¡Increíble! Pedí una maqueta de mi coche soñado y el nivel de detalle es espectacular. 100% recomendado."</p>
                        <span class="autor-testimonio">- Carlos G.</span>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="tarjeta-testimonio h-100">
                        <p class="cita-testimonio">"El servicio de diseño personalizado es genial. Captaron mi idea a la primera y la figura de Aatrox quedó perfecta."</p>
                        <span class="autor-testimonio">- Laura M.</span>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="tarjeta-testimonio h-100">
                        <p class="cita-testimonio">"Compré mi primera impresora 3D aquí y el soporte fue excelente. Muy contenta con la Ender 3."</p>
                        <span class="autor-testimonio">- Javier R.</span>
                    </div>
                </div>
            </div>
        </div>
      </section>

    </div>
  </MainLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import MainLayout from '../layouts/MainLayout.vue';
import axios from '../api/axios'; 

const products = ref([]);
const loading = ref(true);

// --- FETCH DATA ---
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

// --- LOGICA CATEGORIAS CORREGIDA ---
const impresoras = computed(() => {
    return products.value.filter(p => 
        p.category && p.category.toLowerCase() === 'impresoras'
    );
});

const destacados = computed(() => {
    return products.value.filter(p => 
        !p.category || p.category.toLowerCase() !== 'impresoras'
    );
});

const ofertaDia = computed(() => {
    if (products.value.length === 0) return null;
    return products.value.find(p => p.id === 1) || products.value[0];
});

// --- HELPERS VISUALES ---
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

// --- LIFECYCLE ---
onMounted(() => {
    fetchProducts();

    // --- CHATBOT N8N CON PROXY (Sin CORS) ---
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = 'https://cdn.jsdelivr.net/npm/@n8n/chat/dist/style.css';
    document.head.appendChild(link);

    import('https://cdn.jsdelivr.net/npm/@n8n/chat/dist/chat.bundle.es.js')
        .then(module => {
            module.createChat({
                // CAMBIO IMPORTANTE: Usamos la ruta relativa /n8n/...
                // Vite transformará esto a http://localhost:5678/webhook/...
                webhookUrl: '/n8n/webhook/2f4e8def-1604-43d9-a0c4-5677de10f699/chat'
            });
        })
        .catch(err => {
            console.error('Error cargando el chatbot de n8n:', err);
        });
});
</script>

<style>
/* Estilos Globales para Animaciones (Si no están en styles.css) */
@keyframes scrollText {
  0% { transform: translateX(0%); }
  100% { transform: translateX(-100%); }
}
@keyframes pulseGlow {
  0% { transform: scale(1); text-shadow: 0 0 0px rgba(255, 255, 255, 0.2); }
  50% { transform: scale(1.05); text-shadow: 0 0 12px rgba(255, 255, 255, 0.6); }
  100% { transform: scale(1); text-shadow: 0 0 0px rgba(255, 255, 255, 0.2); }
}
@keyframes bounce {
  0%,20%,50%,80%,100% { transform: translateY(0); }
  40% { transform: translateY(20px); }
  60% { transform: translateY(15px); }
}
.animate-pulse { animation: pulse 2s infinite; }
@keyframes pulse {
    0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
    70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
    100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
}

/* Ajuste para que el overlay de agotado funcione */
.tarjeta-producto {
  position: relative;
  overflow: hidden;
}
.overlay-agotado {
    position: absolute; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(255,255,255,0.6); z-index: 5;
    display: flex; align-items: center; justify-content: center;
}
.badge-agotado {
    background: #dc3545; color: white; padding: 10px 20px;
    font-weight: bold; transform: rotate(-15deg); font-size: 1.2rem; border-radius: 5px;
}
.badge-oferta {
    position: absolute; top: 10px; right: 10px; z-index: 4;
}
</style>