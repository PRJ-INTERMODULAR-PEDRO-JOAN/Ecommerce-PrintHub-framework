<template>
    <footer class="pie-pagina">
      <div class="pie-pagina-contenedor">
        
        <div class="pie-pagina-izquierda">
          <h2 class="logo">Print<span class="resaltado">Hub</span></h2><br />
          <div>
            <a class="iconos-sociales" href="#">
              <img src="/img/facebook.svg" alt="Facebook"/>
            </a>
            <a class="iconos-sociales" href="#">
              <img src="/img/linkedin.svg" alt="LinkedIn"/>
            </a>
            <a class="iconos-sociales" href="#">
              <img src="/img/youtube.svg" alt="YouTube"/>
            </a>
            <a class="iconos-sociales" href="#">
              <img src="/img/insta.svg" alt="Instagram"/>
            </a>
          </div>
        </div>
  
        <div class="pie-pagina-centro">
          <img src="/img/fotterImage.jpg" alt="Imagen Global 3D" />
        </div>
  
        <div class="pie-pagina-derecha">
          <div class="servicios">
            <h3>Servicios y productos</h3>
            <ul>
              <li><a href="#">Fabricación aditiva 3D</a></li>
              <li><a href="#">Servicio de Impresión 3D</a></li>
              <li><a href="#">Prototipado 3D</a></li>
              <li><a href="#">Ingeniería y diseño</a></li>
            </ul>
          </div>
          <div class="contacto">
            <h3>Contacto</h3>
            <ul>
              <li>📞 (+34) 951 753 852</li>
              <li>📧 <a href="mailto:printhub@contact.me">printhub@contact.me</a></li>
              <li>📍 Partida Cotes Altes, 27, 03804 Alcoi, Alicante</li>
              <li>🕒 Lunes – Viernes: 8:00 – 13:30</li>
            </ul>
          </div>
        </div>
  
      </div>
    </footer>
  </template>
  
  <script setup>
  // Lógica vacía como solicitado
  </script>
  
  <style scoped>
.pie-pagina {
    background: #e8eff5;
    padding: 40px 20px;
    border-top: 1px solid #d0dbe8;
    font-family: 'Inter', sans-serif;
    color: #333;
  }
  
  .pie-pagina-contenedor {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 40px;
    align-items: center;
    text-align: center;
  }
  
  .pie-pagina-izquierda img {
    width: 140px;
    margin-bottom: 15px;
  }
  
  
  .iconos-sociales img {
    width: 38px;
    height: 38px;
    margin: 0 8px;
    transition: transform 0.3s;
  }
  
  .iconos-sociales img:hover{
    transform: scale(1.2);
  }
  
  .pie-pagina-centro img {
    max-width: 320px;
    border-radius: 12px;
  }
  
  .pie-pagina-derecha {
    text-align: left;
  }
  
  .pie-pagina-derecha h3 {
    font-size: 18px;
    margin-bottom: 10px;
    color: #FF6B00;
  }
  
  .pie-pagina-derecha ul {
    list-style: none;
    padding: 0;
  }
  
  .pie-pagina-derecha li {
    margin-bottom: 8px;
    font-size: 14px;
  }
  
  .pie-pagina-derecha a {
    color: #333;
    text-decoration: none;
  }
  
  .pie-pagina-derecha a:hover {
    color: #007BFF;
  }
  
  .logo {
    margin-top: 10px;
    font-size: 2.5rem;
    color: #000000;
    font-family: 'Chakra Petch', sans-serif;
    font-weight: 700;
    text-transform: uppercase;
  }

  @media (max-width: 900px) {
    .pie-pagina-contenedor {
      grid-template-columns: 1fr;
      text-align: center;
    }
    .pie-pagina-derecha { text-align: center; }
  }
  </style>