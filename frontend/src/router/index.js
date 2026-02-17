import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth' // Asegúrate de importar tu store de auth
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import DashboardView from '../views/DashboardView.vue'
import GalleryView from '../views/GalleryView.vue'
import ProductsView from '../views/ProductsView.vue'
import ProductDetail from '../views/ProductDetail.vue'
import ProductEdit from '../views/ProductEdit.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView,
      meta: { guest: true }
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView,
      meta: { guest: true }
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: DashboardView,
      meta: { requiresAuth: true }
    },
    {
      path: '/gallery',
      name: 'gallery',
      component: GalleryView
    },
    {
      path: '/products',
      name: 'products',
      component: ProductsView
    },
    {
      path: '/products/:id',
      name: 'product.show',
      component: ProductDetail,
      props: true
    },
    {
      path: '/products/:id/edit',
      name: 'product.edit',
      component: ProductEdit,
      props: true,
      meta: { requiresAuth: true, requiresAdmin: true } // Requiere auth y rol admin
    },
    { 
      path: '/:pathMatch(.*)*', 
      redirect: '/' 
    }
  ]
})

// --- GUARDIA DE NAVEGACIÓN ---
router.beforeEach((to, from, next) => {
  const auth = useAuthStore(); // Accedemos al store de autenticación
  const isAuthenticated = !!localStorage.getItem('token');
  const isAdmin = auth.user && auth.user.role === 'admin'; // Verificamos el rol

  // 1. Rutas que requieren Login y NO estás logueado
  if (to.meta.requiresAuth && !isAuthenticated) {
    next({ name: 'login' });
  } 
  // 2. Rutas que requieren ser Admin (como Edit) y NO lo eres
  else if (to.meta.requiresAdmin && !isAdmin) {
    next({ name: 'home' }); // Redirige a home si no es admin
  }
  // 3. Rutas para invitados y SÍ estás logueado
  else if (to.meta.guest && isAuthenticated) {
    next({ name: 'dashboard' });
  } 
  // 4. Permitir navegación
  else {
    next();
  }
});

export default router