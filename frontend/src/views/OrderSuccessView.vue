<template>
    <MainLayout>
        <div class="container py-5 row justify-content-center m-auto">
            
            <div class="col-12 text-center mb-5">
                <div class="position-relative d-inline-block" style="width: 300px;">
                    <div class="progress" style="height: 3px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%;"></div>
                    </div>
                    <div class="position-absolute top-0 start-0 translate-middle btn btn-sm btn-success rounded-circle fw-bold d-flex align-items-center justify-content-center" style="width: 35px; height:35px;">✓</div>
                    <div class="position-absolute top-0 start-50 translate-middle btn btn-sm btn-success rounded-circle fw-bold d-flex align-items-center justify-content-center" style="width: 35px; height:35px;">✓</div>
                    <div class="position-absolute top-0 start-100 translate-middle btn btn-sm btn-success rounded-circle fw-bold d-flex align-items-center justify-content-center" style="width: 35px; height:35px;">✓</div>
                </div>
                <div class="mt-3 text-success fw-bold">PROCESO FINALIZADO</div>
            </div>

            <div class="row justify-content-center" v-if="order">
                <div class="col-md-8 text-center">
                    <div class="card border-0 shadow-lg p-5 rounded-4 bg-white">
                        <div class="mb-4 display-1">🎉</div>
                        
                        <h1 class="fw-bold text-success mb-3">¡Gracias por tu compra!</h1>
                        <p class="lead text-muted mb-5">
                            Tu pedido <strong>#{{ order.id }}</strong> se ha registrado correctamente.
                        </p>
                        
                        <div class="bg-light p-4 rounded-4 text-start d-inline-block w-100 border">
                            <h5 class="fw-bold border-bottom pb-2 mb-3">Resumen del Pedido</h5>
                            
                            <div class="row mb-2">
                                <div class="col-4 text-muted">Total:</div>
                                <div class="col-8 fw-bold text-dark">{{ order.total_price }} €</div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-4 text-muted">Dirección:</div>
                                <div class="col-8 text-dark">{{ order.shipping_address }}</div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <router-link :to="{ name: 'home' }" class="btn btn-dark btn-lg rounded-pill px-5 shadow-sm">
                                Volver al Inicio
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
            
            <div v-else class="text-center py-5">
                <p>Cargando información del pedido o redirigiendo...</p>
                <div class="spinner-border text-primary" role="status"></div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useCartStore } from '../stores/cart';
import { useRouter } from 'vue-router';
import MainLayout from '../layouts/MainLayout.vue';

const cartStore = useCartStore();
const router = useRouter();

const order = computed(() => cartStore.order);

onMounted(() => {
    if(!cartStore.order) {
        // Si no hay pedido (recarga), volver al home tras 3 segundos
        setTimeout(() => {
             router.push('/');
        }, 3000);
    }
});
</script>