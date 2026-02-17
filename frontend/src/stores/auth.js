import { defineStore } from 'pinia';
import axios from '../api/axios'; 
import router from '../router'; 

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
    loading: false,
    errors: null
  }),
  actions: {
    // LOGIN
    async login(credentials) {
      this.loading = true;
      this.errors = null;

      // Limpieza preventiva
      localStorage.removeItem('token');
      this.token = null;
      this.user = null;
      delete axios.defaults.headers.common['Authorization'];

      try {
        // Petición al backend
        const response = await axios.post('/api/login', credentials);
        
        // Guardar datos recibidos
        this.token = response.data.accessToken;
        this.user = response.data.user;
        
        // Persistir Token
        localStorage.setItem('token', this.token);
        
        // Redirigir
        router.push('/dashboard'); 
        
      } catch (error) {
        if (error.response && error.response.status === 401) {
            this.errors = { email: ['Credenciales incorrectas.'] };
        } else if (error.response && error.response.status === 422) {
            this.errors = error.response.data.errors;
        } else {
            console.error(error);
            this.errors = { email: ['Error del servidor.'] };
        }
        throw error;
      } finally {
        this.loading = false;
      }
    },

    // OBTENER USUARIO (Para recargar la página)
    async fetchUser() {
      if (!this.token) return;
      
      this.loading = true;
      try {
        // Laravel usará el token del header para identificar al usuario
        const response = await axios.get('/api/user');
        this.user = response.data;
      } catch (error) {
        // Si el token es inválido o expiró
        this.user = null;
        this.token = null;
        localStorage.removeItem('token');
        router.push('/login');
      } finally {
        this.loading = false;
      }
    },

    // LOGOUT
    async logout() {
        try {
          // Intentamos enviar cookies por si acaso existen
          await axios.post('/api/logout', {}, { withCredentials: true });
        } catch (error) {
          console.error('Error al cerrar sesión', error);
        } finally {
          this.user = null;
          this.token = null;
          localStorage.removeItem('token');
          delete axios.defaults.headers.common['Authorization'];
          
          router.push('/login'); 
        }
      }
  }
});