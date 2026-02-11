<template>
    <MainLayout>
      <div class="login-container">
        <div class="login-card">
          <h2>Iniciar Sesión</h2>
          <p class="subtitle">Bienvenido de nuevo a PrintHub</p>
  
          <form @submit.prevent="handleLogin">
            
            <div class="form-group">
              <label>Correo Electrónico</label>
              <input v-model="form.email" type="email" placeholder="tu@email.com" required>
              <span v-if="auth.errors?.email" class="error">{{ auth.errors.email[0] }}</span>
            </div>
  
            <div class="form-group">
              <label>Contraseña</label>
              <input v-model="form.password" type="password" placeholder="••••••••" required>
            </div>
  
            <button type="submit" :disabled="auth.loading" class="btn-login">
              {{ auth.loading ? 'Cargando...' : 'Entrar' }}
            </button>
  
          </form>
  
          <div class="login-footer">
            <p>¿No tienes cuenta? <a href="/register">Regístrate aquí</a></p>
            <p class="admin-link">
              ¿Eres Administrador? <a href="http://localhost/login">Acceso Admin</a>
            </p>
          </div>
        </div>
      </div>
    </MainLayout>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import MainLayout from '../layouts/MainLayout.vue';
  import { useAuthStore } from '../stores/auth';
  
  const auth = useAuthStore();
  
  const form = ref({
      email: '',
      password: '',
      remember: true
  });
  
  const handleLogin = async () => {
    try {
    await auth.login(form.value);
    // redireccionar...
  } catch (error) {
    console.error("Error en login:", error);
    if (error.response && error.response.status === 500) {
      alert("Error interno del servidor. Intente más tarde.");
    } else {
      alert("Usuario o contraseña incorrectos.");
    }
  }  };
  </script>
  
  <style scoped>
  .login-container {
      min-height: 80vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: #f4f4f4;
      padding: 20px;
  }
  
  .login-card {
      background: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
      text-align: center;
  }
  
  h2 { margin-bottom: 10px; color: #333; }
  .subtitle { color: #666; margin-bottom: 30px; font-size: 0.9rem; }
  
  .form-group { margin-bottom: 20px; text-align: left; }
  .form-group label { display: block; margin-bottom: 5px; font-weight: 600; font-size: 0.9rem; }
  .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; }
  
  .btn-login {
      width: 100%;
      padding: 12px;
      background: linear-gradient(90deg, #FF6B00, #ff8c42);
      color: white;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
  }
  .btn-login:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(255,107,0,0.3); }
  
  .error { color: red; font-size: 0.8rem; margin-top: 5px; display: block; }
  
  .login-footer { margin-top: 20px; font-size: 0.9rem; border-top: 1px solid #eee; padding-top: 20px;}
  .login-footer a { color: #007BFF; text-decoration: none; }
  .admin-link { margin-top: 10px; font-size: 0.8rem; color: #999; }
  </style>