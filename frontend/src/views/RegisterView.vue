<template>
    <MainLayout>
      <div class="register-container d-flex align-items-center justify-content-center py-5" style="min-height: 80vh; background-color: #f8f9fa;">
        
        <div class="card shadow-lg border-0 rounded-4" style="max-width: 500px; width: 100%;">
          <div class="card-body p-5">
              
              <div class="text-center mb-4">
                  <img src="/img/logoPrintHub.jpeg" alt="Logo" width="80" class="rounded-circle mb-3 shadow-sm">
                  <h3 class="fw-bold">Crear Cuenta</h3>
                  <p class="text-muted">Únete a la comunidad de PrintHub</p>
              </div>
  
              <form @submit.prevent="handleRegister">
                  
                  <div class="mb-3">
                      <label class="form-label small fw-bold text-muted">Nombre</label>
                      <input type="text" v-model="form.name" class="form-control bg-light border-0 py-2" :class="{ 'is-invalid': errors.name }" required autofocus>
                      <div v-if="errors.name" class="invalid-feedback">{{ errors.name[0] }}</div>
                  </div>
  
                  <div class="mb-3">
                      <label class="form-label small fw-bold text-muted">Apellidos</label>
                      <input type="text" v-model="form.surname" class="form-control bg-light border-0 py-2" :class="{ 'is-invalid': errors.surname }">
                      <div v-if="errors.surname" class="invalid-feedback">{{ errors.surname[0] }}</div>
                  </div>
  
                  <div class="mb-3">
                      <label class="form-label small fw-bold text-muted">Teléfono</label>
                      <input type="tel" v-model="form.phone" class="form-control bg-light border-0 py-2" :class="{ 'is-invalid': errors.phone }">
                      <div v-if="errors.phone" class="invalid-feedback">{{ errors.phone[0] }}</div>
                  </div>
  
                  <div class="mb-3">
                      <label class="form-label small fw-bold text-muted">Email</label>
                      <input type="email" v-model="form.email" class="form-control bg-light border-0 py-2" :class="{ 'is-invalid': errors.email }" required>
                      <div v-if="errors.email" class="invalid-feedback">{{ errors.email[0] }}</div>
                  </div>
  
                  <div class="mb-3">
                      <label class="form-label small fw-bold text-muted">Contraseña</label>
                      <input type="password" v-model="form.password" class="form-control bg-light border-0 py-2" :class="{ 'is-invalid': errors.password }" required>
                      <div v-if="errors.password" class="invalid-feedback">{{ errors.password[0] }}</div>
                  </div>
  
                  <div class="mb-4">
                      <label class="form-label small fw-bold text-muted">Confirmar Contraseña</label>
                      <input type="password" v-model="form.password_confirmation" class="form-control bg-light border-0 py-2" required>
                  </div>
  
                  <div class="d-grid mb-3">
                      <button type="submit" class="btn btn-dark fw-bold py-2 rounded-3" :disabled="loading">
                          <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                          REGISTRARSE
                      </button>
                  </div>
  
                  <div class="text-center small">
                      <span class="text-muted">¿Ya tienes cuenta?</span>
                      <router-link to="/login" class="text-decoration-none fw-bold ms-1" style="color: #ff6b00;">
                          Inicia Sesión
                      </router-link>
                  </div>
  
              </form>
          </div>
        </div>
  
      </div>
    </MainLayout>
  </template>
  
  <script setup>
  import { reactive, ref } from 'vue';
  import { useRouter } from 'vue-router';
  import axios from '../api/axios';
  import { useAuthStore } from '../stores/auth';
  import MainLayout from '../layouts/MainLayout.vue';
  
  const router = useRouter();
  const authStore = useAuthStore();
  
  // AÑADIDOS surname y phone al objeto reactivo
  const form = reactive({
      name: '',
      surname: '', 
      phone: '',
      email: '',
      password: '',
      password_confirmation: ''
  });
  
  const errors = ref({});
  const loading = ref(false);
  
  const handleRegister = async () => {
      loading.value = true;
      errors.value = {};
  
      // 1. Limpieza de sesión previa
      localStorage.removeItem('token');
      authStore.user = null;
      authStore.token = null;
      delete axios.defaults.headers.common['Authorization'];
  
      try {
          // 2. Petición al backend
          const response = await axios.post('/api/register', form);
          
          // 3. Login automático
          if (response.data.accessToken) {
              authStore.token = response.data.accessToken;
              authStore.user = response.data.user;
              localStorage.setItem('token', response.data.accessToken);
              router.push('/dashboard');
          }
  
      } catch (error) {
          if (error.response && error.response.status === 422) {
              errors.value = error.response.data.errors;
          } else {
              console.error(error);
              alert('Ocurrió un error inesperado al registrarse.');
          }
      } finally {
          loading.value = false;
      }
  };
  </script>