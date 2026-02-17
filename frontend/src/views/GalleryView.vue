<template>
    <MainLayout>
      <div class="gallery-page">
        
        <a href="#destacados" class="enlace-banner" style="display:block;">
        <div class="banner">
          <div class="banner-texto">
            ¡Oferta especial de Navidad! 🦌🎅 Solo por tiempo limitado.
          </div>
        </div>
      </a>
  
        <header class="gallery-header text-center py-5">
          <h1 class="fw-bold display-4">GALERÍA DE PROYECTOS</h1>
          <div class="d-flex justify-content-center mt-3">
               <img src="/img/logoPrintHub.jpeg" alt="Logo PrintHub" width="120" class="rounded-circle shadow-sm" />
          </div>
        </header>
  
        <div class="container pb-5">
          
          <div class="menu-galeria mb-5 sticky-top bg-white py-3" style="z-index: 90; top: 0;">
              <ul class="d-flex justify-content-center gap-2 list-unstyled flex-wrap m-0">
                  <li><a href="#" class="btn btn-dark rounded-pill px-4" @click.prevent>Todo</a></li>
                  <li v-for="seccion in secciones" :key="seccion.id">
                      <a :href="`#${seccion.id}`" class="btn btn-outline-dark rounded-pill px-4">{{ seccion.titulo }}</a>
                  </li>
                  <li><a href="#" class="btn btn-outline-dark rounded-pill px-4" @click.prevent>Modelos</a></li>
                  <li><a href="#" class="btn btn-outline-dark rounded-pill px-4" @click.prevent>Otros</a></li>
              </ul>
          </div>
  
          <div class="galeria-content">
              <section v-for="(seccion, sIndex) in secciones" :key="seccion.id" :id="seccion.id" class="mb-5 section-block">
                  
                  <div class="d-flex align-items-center mb-4">
                      <h2 class="text-uppercase fw-bold m-0 me-3" style="color: #ff6b00;">{{ seccion.titulo }}</h2>
                      <div class="flex-grow-1 border-bottom" style="border-color: #ddd;"></div>
                  </div>
                  
                  <div class="carousel-container shadow-lg rounded-4">
                      
                      <button class="carousel-btn prev" @click="prevSlide(sIndex)" aria-label="Anterior">
                          <span class="arrow">&#10094;</span>
                      </button>
                      
                      <div class="carousel-viewport">
                          <div class="carousel-track" :style="{ transform: `translateX(-${currentIndices[sIndex] * 100}%)` }">
                              <div v-for="(img, iIndex) in seccion.imagenes" :key="iIndex" class="carousel-slide">
                                  <img :src="getImagePath(img)" :alt="seccion.titulo" class="carousel-image">
                              </div>
                          </div>
                      </div>
                      
                      <button class="carousel-btn next" @click="nextSlide(sIndex)" aria-label="Siguiente">
                          <span class="arrow">&#10095;</span>
                      </button>
  
                      <div class="carousel-dots-container">
                          <div class="carousel-dots">
                              <span 
                                  v-for="(_, iIndex) in seccion.imagenes" 
                                  :key="iIndex" 
                                  class="dot" 
                                  :class="{ active: currentIndices[sIndex] === iIndex }"
                                  @click="goToSlide(sIndex, iIndex)"
                              ></span>
                          </div>
                      </div>
  
                  </div>
              </section>
          </div>
  
        </div>
      </div>
    </MainLayout>
  </template>
  
  <script setup>
  import { ref, reactive } from 'vue';
  import MainLayout from '../layouts/MainLayout.vue';
  
  // --- DATOS SIMULADOS ---
  const secciones = reactive([
      {
          id: 'videojuegos',
          titulo: 'Videojuegos',
          imagenes: ['juego1.jpg', 'juego2.jpg', 'juego3.jpg', 'juego4.jpg']
      },
      {
          id: 'arquitectura',
          titulo: 'Arquitectura',
          imagenes: ['puente1.jpg', 'puente2.jpg', 'puente3.jpg', 'puente4.jpg']
      },
      {
          id: 'automoviles',
          titulo: 'Automóviles',
          imagenes: ['coche1.jpg', 'coche2.jpg', 'coche3.jpg', 'coche4.jpg']
      }
  ]);
  
  // --- LÓGICA DEL CARRUSEL ---
  const currentIndices = ref(secciones.map(() => 0));
  
  const nextSlide = (sectionIndex) => {
      const totalImages = secciones[sectionIndex].imagenes.length;
      currentIndices.value[sectionIndex] = (currentIndices.value[sectionIndex] + 1) % totalImages;
  };
  
  const prevSlide = (sectionIndex) => {
      const totalImages = secciones[sectionIndex].imagenes.length;
      currentIndices.value[sectionIndex] = (currentIndices.value[sectionIndex] - 1 + totalImages) % totalImages;
  };
  
  const goToSlide = (sectionIndex, imageIndex) => {
      currentIndices.value[sectionIndex] = imageIndex;
  };
  
  const getImagePath = (img) => {
      if (!img) return '/img/marcaDeAgua.png';
      return img.startsWith('http') ? img : `/img/${img}`;
  };
  </script>
  
  <style scoped>
  .gallery-page {
      background-color: #f9f9f9;
  }
  
  /* Banner */
  .banner {
  background: linear-gradient(90deg, #FF6B00, #ff7b1d);
  color: #fff;
  height: 40px;
  display: flex;
  justify-content: center;
  align-items: center;
  font-weight: bold;
  font-family: 'Roboto Mono', monospace;
  overflow: hidden;
  white-space: nowrap;
  text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
}

.banner-texto {
  display: inline-block;
  padding-left: 100%;
  animation: scrollText 40s linear infinite;
  color: #000;
}
  
  /* Carrusel Contenedor Principal */
  .carousel-container {
      position: relative;
      width: 100%;
      max-width: 1000px; /* Un poco más ancho */
      margin: 0 auto;
      background: #000;
      overflow: hidden; /* Vital para que no se salgan las imágenes */
  }
  
  /* Viewport y Track */
  .carousel-viewport {
      width: 100%;
      overflow: hidden;
  }
  
  .carousel-track {
      display: flex;
      transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1); /* Transición más suave */
      width: 100%;
  }
  
  /* Diapositiva Individual */
  .carousel-slide {
      flex: 0 0 100%; /* Forzar que ocupe el 100% del ancho siempre */
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #f0f0f0;
  }
  
  /* Imagen Responsiva */
  .carousel-image {
      width: 100%;
      height: 300px; /* Altura móvil */
      object-fit: cover;
      display: block;
  }
  
  @media (min-width: 768px) {
      .carousel-image {
          height: 550px; /* Altura Desktop */
      }
  }
  
  /* Botones de Navegación */
  .carousel-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(0, 0, 0, 0.3);
      color: white;
      border: none;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      cursor: pointer;
      z-index: 10;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      backdrop-filter: blur(2px);
  }
  
  .carousel-btn:hover {
      background: #ff6b00;
      transform: translateY(-50%) scale(1.1);
  }
  
  .carousel-btn.prev { left: 20px; }
  .carousel-btn.next { right: 20px; }
  
  .arrow {
      font-size: 1.5rem;
      line-height: 1;
      padding-bottom: 3px; /* Pequeño ajuste visual */
  }
  
  /* Puntos / Dots */
  .carousel-dots-container {
      position: absolute;
      bottom: 20px;
      left: 0;
      width: 100%;
      display: flex;
      justify-content: center;
      z-index: 10;
  }
  
  .carousel-dots {
      background: rgba(0, 0, 0, 0.4);
      padding: 8px 16px;
      border-radius: 20px;
      display: flex;
      gap: 10px;
      backdrop-filter: blur(4px);
  }
  
  .dot {
      width: 10px;
      height: 10px;
      background: rgba(255, 255, 255, 0.5);
      border-radius: 50%;
      cursor: pointer;
      transition: all 0.3s ease;
  }
  
  .dot:hover {
      background: rgba(255, 255, 255, 0.9);
  }
  
  .dot.active {
      background: #ff6b00;
      transform: scale(1.3);
      box-shadow: 0 0 5px rgba(255, 107, 0, 0.5);
  }
  </style>