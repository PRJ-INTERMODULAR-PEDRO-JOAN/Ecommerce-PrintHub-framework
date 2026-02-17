<template>
    <div class="container my-5" v-if="form">
      <div class="card shadow-lg col-md-8 mx-auto">
        <div class="card-header bg-warning fw-bold">✏️ Editar Producto: {{ form.name }}</div>
        <div class="card-body">
          <form @submit.prevent="handleUpdate">
            <div class="mb-3">
              <label class="form-label fw-bold">Nombre</label>
              <input v-model="form.name" type="text" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-bold">Descripción</label>
              <textarea v-model="form.description" class="form-control" rows="4"></textarea>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Precio (€)</label>
                <input v-model="form.price" type="number" step="0.01" class="form-control">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Stock</label>
                <input v-model="form.stock" type="number" class="form-control">
              </div>
            </div>
            <div class="d-flex justify-content-between mt-4">
              <button type="button" @click="$router.back()" class="btn btn-secondary">Cancelar</button>
              <button type="submit" class="btn btn-primary fw-bold">💾 Guardar Cambios</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import { useRouter } from 'vue-router';
  
  const props = defineProps(['id']);
  const router = useRouter();
  const form = ref(null);
  
  onMounted(async () => {
    const res = await axios.get(`/api/products/${props.id}`);
    form.value = res.data;
  });
  
  const handleUpdate = async () => {
  try {
    // Asegúrate de que props.id tenga el valor correcto
    const response = await axios.put(`/api/products/${props.id}`, {
        name: form.value.name,
        description: form.value.description,
        price: form.value.price,
        stock: form.value.stock
    });
    
    alert("¡Producto actualizado con éxito!");
    router.push({ name: 'product.show', params: { id: props.id } });
  } catch (err) {
    console.error(err);
    // Esto te ayudará a ver por qué da 401 o 500 en la consola
    alert(err.response?.data?.message || "Error al actualizar");
  }
};
  </script>