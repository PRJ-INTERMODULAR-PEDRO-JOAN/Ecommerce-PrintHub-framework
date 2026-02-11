<template>
    <nav class="navbar">
      <div class="logo">PrintHub</div>
      <div class="links">
          <router-link to="/">Inicio</router-link>
          <router-link to="/products">Productos</router-link>
          <router-link to="/cart">Carrito</router-link>
      </div>
      <div class="auth">
          <a href="http://localhost/login">Iniciar Sesión</a>
      </div>
    </nav>
  </template>
  
  <style scoped>
  .navbar { display: flex; justify-content: space-between; padding: 1rem; background: #333; color: white; }
  .links a { color: white; margin: 0 10px; text-decoration: none; }
  </style>