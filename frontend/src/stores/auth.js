import { defineStore } from 'pinia';
import axios from '../api/axios'; // Asegúrate que esta ruta sea correcta
import router from '../router'; 

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    loading: false,
    errors: null
  }),
  actions: {
    // OBTENER USUARIO ACTUAL
    async fetchUser() {
      this.loading = true;
      try {
        const response = await axios.get('/api/user');
        this.user = response.data;
      } catch (error) {
        this.user = null;
      } finally {
        this.loading = false;
      }
    },

    // LOGIN
    async login(credentials) {
      this.loading = true;
      this.errors = null;
      try {
        // 1. OBLIGATORIO: Pedir la cookie CSRF a Laravel antes de nada
        // Esto evita el error 419
        await axios.get('/sanctum/csrf-cookie');
        
        // 2. Hacer login
        await axios.post('/api/login', credentials);
        
        // 3. Si todo va bien, obtener datos del usuario y redirigir
        await this.fetchUser();
        
        // Redirigir al usuario (Dashboard o Home)
        router.push('/dashboard'); 
        
      } catch (error) {
        if (error.response && error.response.status === 422) {
            // Error de validación (contraseña incorrecta, etc)
            this.errors = error.response.data.errors;
        } else if (error.response && error.response.status === 419) {
            // Token expirado
            this.errors = { email: ['La sesión ha expirado, recarga la página.'] };
        } else {
            // Otros errores (500, etc)
            console.error(error);
            this.errors = { email: ['Error del servidor. Inténtalo más tarde.'] };
        }
        throw error;
      } finally {
        this.loading = false;
      }
    },

    // LOGOUT
    async logout() {
      try {
        await axios.post('/api/logout');
        this.user = null;
        router.push('/login'); 
      } catch (error) {
        console.error('Error al cerrar sesión', error);
      }
    }
  }
});