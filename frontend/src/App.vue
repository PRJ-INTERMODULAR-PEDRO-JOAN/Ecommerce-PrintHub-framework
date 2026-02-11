<template>
  <router-view v-slot="{ Component }">
      <component :is="Component" />
  </router-view>
</template>

<style>
/* Estilos globales */
body { margin: 0; font-family: sans-serif; }
</style>