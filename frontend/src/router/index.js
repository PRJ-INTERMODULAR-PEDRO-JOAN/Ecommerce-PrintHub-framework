import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue' // <--- NUEVO IMPORT
import DashboardView from '../views/DashboardView.vue'
import GalleryView from '../views/GalleryView.vue'   // <--- NUEVO IMPORT
import ProductsView from '../views/ProductsView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    // --- LOGIN (Solo Invitados) ---
    {
      path: '/login',
      name: 'login',
      component: LoginView,
      meta: { guest: true }
    },
    // --- REGISTER (Solo Invitados) ---
    {
      path: '/register',
      name: 'register',
      component: RegisterView,
      meta: { guest: true }
    },
    // --- DASHBOARD (Solo Autenticados) ---
    {
      path: '/dashboard',
      name: 'dashboard',
      component: DashboardView,
      meta: { requiresAuth: true }
    },
    // --- GALERÍA (Pública) ---
    {
      path: '/gallery', // O '/gallery' si prefieres
      name: 'gallery',
      component: GalleryView
    },
    // --- PRODUCTOS (Pública) ---
    {
      path: '/products',
      name: 'products',
      component: ProductsView
    },
    // Redirección por defecto para rutas no encontradas (opcional)
    { 
      path: '/:pathMatch(.*)*', 
      redirect: '/' 
    }
  ]
})

// --- GUARDIA DE NAVEGACIÓN ---
router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem('token');

  // 1. Rutas que requieren Login y NO estás logueado -> Login
  if (to.meta.requiresAuth && !isAuthenticated) {
    next({ name: 'login' });
  } 
  // 2. Rutas para invitados (Login/Register) y SÍ estás logueado -> Dashboard
  else if (to.meta.guest && isAuthenticated) {
    next({ name: 'dashboard' });
  } 
  // 3. Resto de rutas -> Permitir
  else {
    next();
  }
});

export default router