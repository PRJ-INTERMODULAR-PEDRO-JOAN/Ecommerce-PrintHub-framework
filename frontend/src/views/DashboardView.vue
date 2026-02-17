<template>
    <MainLayout>
      <div class="dashboard-container">
        
        <div class="back-btn-wrapper">
          <router-link to="/" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold">
              ← Volver al Inicio
          </router-link>
        </div>
  
        <div class="card shadow-lg border-0 rounded-4 dashboard-card">
          <div class="card-body p-4 p-md-5">
              
            <div class="text-center mb-4">
                <img src="/img/logoPrintHub.jpeg" width="60" class="rounded-circle mb-3 shadow-sm" alt="Logo">
                <h3 class="fw-bold">Editar mis datos</h3>
                <p class="text-muted">Actualiza tu información personal</p>
            </div>
  
            <div v-if="statusMessage" class="alert alert-success text-center py-2 mb-4 rounded-3 small">
                {{ statusMessage }}
            </div>
  
            <form @submit.prevent="updateProfile">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Nombre</label>
                        <input type="text" v-model="profileForm.name" class="form-control bg-light border-0 py-2" :class="{ 'is-invalid': errors.name }" required>
                        <div v-if="errors.name" class="invalid-feedback">{{ errors.name[0] }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Apellidos</label>
                        <input type="text" v-model="profileForm.surname" class="form-control bg-light border-0 py-2" :class="{ 'is-invalid': errors.surname }">
                        <div v-if="errors.surname" class="invalid-feedback">{{ errors.surname[0] }}</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold text-muted">Email</label>
                        <input type="email" v-model="profileForm.email" class="form-control bg-light border-0 py-2" :class="{ 'is-invalid': errors.email }" required>
                        <div v-if="errors.email" class="invalid-feedback">{{ errors.email[0] }}</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold text-muted">Teléfono</label>
                        <input type="tel" v-model="profileForm.phone" class="form-control bg-light border-0 py-2" :class="{ 'is-invalid': errors.phone }">
                        <div v-if="errors.phone" class="invalid-feedback">{{ errors.phone[0] }}</div>
                    </div>
                </div>
  
                <div class="mt-4 pt-2">
                    <button type="submit" class="btn btn-dark w-100 py-2 fw-bold rounded-3" :disabled="loading">
                        <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                        GUARDAR CAMBIOS
                    </button>
                </div>
            </form>
  
            <hr class="my-4 text-muted opacity-25">
  
            <div class="d-grid mb-3">
                <button class="btn btn-light text-muted border-0 py-2 d-flex justify-content-between align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#passCollapse">
                    <span>🔒 Cambiar mi contraseña</span>
                    <span class="small">▼</span>
                </button>
                
                <div class="collapse mt-3" id="passCollapse">
                    <div class="card card-body bg-light border-0 rounded-3">
                        <form @submit.prevent="updatePassword">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Contraseña Actual</label>
                                <input type="password" v-model="passwordForm.current_password" class="form-control border-0" :class="{ 'is-invalid': passwordErrors.current_password }">
                                <div v-if="passwordErrors.current_password" class="invalid-feedback">{{ passwordErrors.current_password[0] }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Nueva Contraseña</label>
                                <input type="password" v-model="passwordForm.password" class="form-control border-0" :class="{ 'is-invalid': passwordErrors.password }">
                                <div v-if="passwordErrors.password" class="invalid-feedback">{{ passwordErrors.password[0] }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Confirmar Contraseña</label>
                                <input type="password" v-model="passwordForm.password_confirmation" class="form-control border-0">
                            </div>
                            <button type="submit" class="btn btn-outline-dark w-100 btn-sm fw-bold">Actualizar Contraseña</button>
                        </form>
                    </div>
                </div>
            </div>
  
            <div class="d-grid">
                <button class="btn btn-outline-danger border-0 py-2 text-start" type="button" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                    ⚠️ Eliminar cuenta
                </button>
            </div>
  
          </div>
        </div>
  
        <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
              <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger">¿Estás seguro?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <p class="text-muted small">
                    Una vez eliminada tu cuenta, todos tus recursos y datos se borrarán permanentemente. 
                    Por favor, ingresa tu contraseña para confirmar.
                </p>
                <form @submit.prevent="deleteAccount">
                    <div class="mb-3">
                        <input type="password" v-model="deleteForm.password" class="form-control bg-light border-0" placeholder="Contraseña" :class="{ 'is-invalid': deleteErrors.password }">
                        <div v-if="deleteErrors.password" class="invalid-feedback">{{ deleteErrors.password[0] }}</div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Eliminar Cuenta</button>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>
  
      </div>
    </MainLayout>
  </template>
  
  <script setup>
  import { ref, onMounted, reactive } from 'vue';
  import MainLayout from '../layouts/MainLayout.vue';
  import { useAuthStore } from '../stores/auth';
  import axios from '../api/axios';
  import { useRouter } from 'vue-router';
  
  // --- ESTADO ---
  const authStore = useAuthStore();
  const router = useRouter();
  const loading = ref(false);
  const statusMessage = ref('');
  
  // Errores separados por formulario para evitar mezclar validaciones
  const errors = ref({});
  const passwordErrors = ref({});
  const deleteErrors = ref({});
  
  // Datos de los formularios
  const profileForm = reactive({
      name: '',
      surname: '',
      email: '',
      phone: ''
  });
  
  const passwordForm = reactive({
      current_password: '',
      password: '',
      password_confirmation: ''
  });
  
  const deleteForm = reactive({
      password: ''
  });
  
  // --- INICIALIZAR DATOS ---
  onMounted(async () => {
      // Si no tenemos el usuario en el store, intentamos cargarlo
      if (!authStore.user) {
          await authStore.fetchUser();
      }
      // Rellenar formulario con datos actuales
      if (authStore.user) {
          profileForm.name = authStore.user.name;
          profileForm.surname = authStore.user.surname || '';
          profileForm.email = authStore.user.email;
          profileForm.phone = authStore.user.phone || '';
      }
  });
  
  // --- 1. ACTUALIZAR PERFIL ---
  const updateProfile = async () => {
      loading.value = true;
      errors.value = {};
      statusMessage.value = '';
  
      try {
          // Asumiendo que crearás esta ruta en Laravel API
          const response = await axios.patch('/api/profile', profileForm);
          
          // Actualizar store con los nuevos datos devueltos
          authStore.user = response.data.user; 
          statusMessage.value = '¡Perfil actualizado con éxito!';
          
          // Quitar mensaje después de 3 seg
          setTimeout(() => statusMessage.value = '', 3000);
      } catch (error) {
          if (error.response && error.response.status === 422) {
              errors.value = error.response.data.errors;
          } else {
              console.error(error);
              alert('Ocurrió un error al actualizar el perfil.');
          }
      } finally {
          loading.value = false;
      }
  };
  
  // --- 2. ACTUALIZAR CONTRASEÑA ---
  const updatePassword = async () => {
      passwordErrors.value = {};
      try {
          await axios.put('/api/password', passwordForm);
          
          statusMessage.value = '¡Contraseña cambiada correctamente!';
          
          // Limpiar formulario
          passwordForm.current_password = '';
          passwordForm.password = '';
          passwordForm.password_confirmation = '';
          
          // Cerrar collapse (opcional, requiere manipular DOM o usar ref bootstrap)
          document.getElementById('passCollapse').classList.remove('show');
          
          setTimeout(() => statusMessage.value = '', 3000);
      } catch (error) {
          if (error.response && error.response.status === 422) {
              passwordErrors.value = error.response.data.errors;
          } else {
              console.error(error);
              alert('Error al cambiar contraseña.');
          }
      }
  };
  
  // --- 3. ELIMINAR CUENTA ---
  const deleteAccount = async () => {
      deleteErrors.value = {};
      if (!confirm('¿Estás SEGURO de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.')) return;
  
      try {
          await axios.delete('/api/profile', {
              data: { password: deleteForm.password } // Axios DELETE body
          });
          
          // Cerrar modal manualmente (truco rápido para limpiar backdrop)
          const modalEl = document.getElementById('deleteAccountModal');
          const modalInstance = bootstrap.Modal.getInstance(modalEl);
          if(modalInstance) modalInstance.hide();
          
          // Logout y redirigir
          authStore.user = null;
          localStorage.removeItem('token');
          router.push('/login');
          
      } catch (error) {
          if (error.response && error.response.status === 422) {
              deleteErrors.value = error.response.data.errors;
          } else {
              console.error(error);
              alert('No se pudo eliminar la cuenta.');
          }
      }
  };
  </script>
  
  <style scoped>
  /* Estilos específicos copiados y adaptados de tu blade */
  .dashboard-container {
      background-color: #f8f9fa;
      min-height: calc(100vh - 80px); /* Ajuste para navbar */
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
      position: relative;
  }
  
  .dashboard-card {
      max-width: 600px;
      width: 100%;
  }
  
  .back-btn-wrapper {
      width: 100%;
      max-width: 600px;
      margin-bottom: 1rem;
  }
  
  @media (max-width: 768px) {
      .back-btn-wrapper {
          text-align: center;
      }
  }
  </style>