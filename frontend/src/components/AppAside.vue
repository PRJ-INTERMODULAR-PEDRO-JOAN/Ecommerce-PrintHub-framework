<template>
  <div>
    <button class="alternar-menu" @click="toggleMenu">☰</button>

    <aside class="barra-lateral" :class="{ 'activa': menuAbierto }">
      
      <div class="barra-lateral-cabecera">
        <h1 class="logo-texto">Print<span class="resaltado">Hub</span></h1>
        <div class="logo">
            <img src="/img/logoPrintHub.jpeg" alt="Logo de PrintHub" />
        </div>
      </div>

      <ul class="iconos-utilidad">
        
        <li>
            <router-link to="/cart" aria-label="Carrito" class="position-relative text-decoration-none">
                🛒
            </router-link>
        </li>
        
        <li v-if="auth.user" class="nav-item dropdown ms-3" style="list-style: none; position: relative;">
            
            <a 
                class="nav-link dropdown-toggle text-black" 
                href="#" 
                role="button" 
                @click.prevent="toggleUserMenu"
            >
                {{ auth.user.name }} ▾
            </a>

            <ul class="dropdown-menu dropdown-menu-end" :class="{ 'show': usuarioMenuAbierto }">
                <li>
                  <router-link class="dropdown-item" to="/dashboard">Mi Perfil</router-link>
                </li>
                <li><hr class="dropdown-divider"></li>
                
                <li>
                    <button 
                        @click="handleLogout" 
                        class="dropdown-item text-danger" 
                        style="background:none; border:none; width:100%; text-align:left;"
                    >
                        Cerrar Sesión
                    </button>
                </li>
            </ul>
        </li>

        <li v-else class="nav-item ms-2" style="list-style: none;">
            <router-link class="btn btn-outline-dark btn-sm px-3" to="/login">
            👤 Login
            </router-link>
        </li>

      </ul>
      
      <h3 class="etiqueta-menu">Menú</h3>
      <nav>
        <ul>
            <li><router-link to="/" @click="closeMenu">Inicio</router-link></li>
            
            <li class="desplegable">
                <a href="#" @click.prevent="toggleSubmenu">Maquetas Personalizadas ▾</a>
                <ul class="contenido-desplegable" :class="{ 'mostrar': submenuAbierto }">
                    <li><router-link to="/products?cat=videojuegos" @click="closeMenu">Videojuegos</router-link></li>
                    <li><router-link to="/products?cat=arquitectura" @click="closeMenu">Arquitectura</router-link></li>
                    <li><router-link to="/products?cat=automoviles" @click="closeMenu">Automóviles</router-link></li>
                </ul>
            </li>

            <li><router-link to="/products" @click="closeMenu">Todos Nuestros Productos</router-link></li>
            <li><router-link to="/#como-funciona" @click="closeMenu">Diseñar Maquetas</router-link></li>
            <li><router-link to="/gallery" @click="closeMenu">Galería de Proyectos</router-link></li>
            <li><router-link to="/#impresoras" @click="closeMenu">Impresoras 3D</router-link></li>
            <li>
              <a 
                href="http://localhost:5678/form/fb9dd885-a08a-4146-a96a-5664b3560d7c" 
                target="_blank"
                @click="closeMenu"
              >
                Formulario Contacto
              </a>
            </li>
             
             <li v-if="auth.user && auth.user.role === 'admin'">
                <a href="http://localhost/admin/import" style="color: black;">Importar Productos</a>
            </li>
        </ul>
      </nav>

    </aside>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '../stores/auth';

const auth = useAuthStore();

const menuAbierto = ref(false);
const submenuAbierto = ref(false);
const usuarioMenuAbierto = ref(false);

const toggleMenu = () => { menuAbierto.value = !menuAbierto.value; };
const toggleSubmenu = () => { submenuAbierto.value = !submenuAbierto.value; };
const toggleUserMenu = () => { usuarioMenuAbierto.value = !usuarioMenuAbierto.value; };

const closeMenu = () => {
  if (window.innerWidth < 768) menuAbierto.value = false;
};

const handleLogout = async () => {
    await auth.logout();
    // Al hacer logout, recargamos la página para que Laravel limpie la sesión y Vue se reinicie
    window.location.href = '/'; 
};

onMounted(() => {
    auth.fetchUser();
});
</script>

<style scoped>
/* =========================================
   ESTILOS COPIADOS DEL ASIDE ORIGINAL
   ========================================= */
.dropdown-menu {
  display: none;
  position: absolute;
  top: 100%;
  right: 0;
  background: white;
  border: 1px solid #ddd;
  border-radius: 8px;
  min-width: 160px;
  z-index: 2000;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.dropdown-menu.show { display: block; }

.dropdown-item {
  display: block;
  width: 100%;
  padding: 0.5rem 1rem;
  font-weight: 400;
  color: #212529;
  text-decoration: none;
  background-color: transparent;
  border: 0;
  cursor: pointer;
}

.dropdown-item:hover { background-color: #f8f9fa; }

.resaltado {
  background: linear-gradient(90deg, #FF6B00, #007BFF);
  color: black;
  padding: 0 6px;
  border-radius: 4px;
  font-weight: 700;
  margin-left: 5px;
}

.barra-lateral {
  position: fixed;
  top: 0;
  right: -260px;
  width: 260px;
  height: 100%;
  background: #ffffff;
  color: #1a1a1a;
  padding: 2rem 1rem;
  transition: right 0.4s ease, box-shadow 0.4s ease;
  z-index: 1000;
  box-shadow: -6px 0 25px rgba(0, 0, 0, 0.08);
  border-left: 3px solid #FF6B00;
  border-radius: 16px 0 0 16px;
  overflow-y: auto;
}

.barra-lateral.activa { right: 0; }

.barra-lateral .logo img {
  width: 130px;
  display: block;
  margin: 0 auto 1.5rem;
  border-radius: 12px;
}

.iconos-utilidad {
  list-style: none;
  display: flex;
  justify-content: center;
  gap: 1rem;
  margin-bottom: 1.5rem;
  padding: 0;
}

.barra-lateral nav ul { list-style: none; padding: 0; }
.barra-lateral nav ul li { margin: 1.2rem 0; }

.barra-lateral nav ul li a, .iconos-utilidad li a {
  color: #222;
  text-decoration: none;
  font-weight: 500;
  font-size: 1.1rem;
  display: block;
  padding: 0.6rem 1rem;
  border-radius: 10px;
  transition: all 0.3s ease;
}

.barra-lateral nav ul li a:hover, .iconos-utilidad li a:hover {
  background: linear-gradient(90deg, #007BFF, #00C6FF);
  color: #fff;
  transform: translateX(-4px);
}

.contenido-desplegable {
  list-style: none;
  padding-left: 15px;
  max-height: 0;
  opacity: 0;
  overflow: hidden;
  transition: all 0.5s ease;
}
.contenido-desplegable.mostrar {
  max-height: 500px;
  opacity: 1;
}

.alternar-menu {
  position: fixed;
  top: 10px;
  right: 10px;
  background: linear-gradient(90deg, #FF6B00, #ff8c42);
  color: #fff;
  border: none;
  padding: 12px 16px;
  font-size: 20px;
  cursor: pointer;
  border-radius: 12px;
  z-index: 1100;
  box-shadow: 0 4px 15px rgba(255, 107, 0, 0.4);
}
</style>