<template>
    <div>
        
      <AppAside />
  
      <main>
        <slot></slot> 
      </main>
  
      <AppFooter />
      
    </div>
  </template>
  
  <script setup>
  import AppAside from '../components/AppAside.vue';
  import AppFooter from '../components/AppFooter.vue';
  </script>
  
  <style scoped>
  
  </style>