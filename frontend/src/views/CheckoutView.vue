<template>
    <MainLayout>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10 col-md-12">
                    
                    <div class="text-start mb-4">
                        <router-link :to="{ name: 'home' }" class="btn btn-outline-secondary btn-sm">
                            &larr; Volver al Inicio
                        </router-link>
                    </div>

                    <h2 class="text-center mb-5 fw-bold text-dark">Finalizar Pedido</h2>

                    <div class="position-relative mx-auto mb-5" style="max-width: 600px;">
                        <div class="progress" style="height: 4px; background-color: #e9ecef;">
                            <div class="progress-bar bg-primary" role="progressbar" :style="{ width: progressWidth, transition: 'width 0.4s ease' }"></div>
                        </div>
                        
                        <button 
                            @click="goToStep(1)"
                            class="position-absolute top-0 start-0 translate-middle btn btn-sm rounded-circle fw-bold shadow d-flex align-items-center justify-content-center border-0"
                            :class="step >= 1 ? 'btn-primary' : 'btn-secondary'" 
                            :style="{ width: '40px', height: '40px', cursor: step > 1 ? 'pointer' : 'default' }"
                        >1</button>

                        <button 
                            @click="goToStep(2)"
                            class="position-absolute top-0 start-50 translate-middle btn btn-sm rounded-circle fw-bold shadow d-flex align-items-center justify-content-center border-0"
                            :class="step >= 2 ? 'btn-primary' : 'btn-secondary'" 
                            :style="{ width: '40px', height: '40px', cursor: step > 2 ? 'pointer' : 'default' }"
                        >2</button>

                        <div class="position-absolute top-0 start-100 translate-middle btn btn-sm rounded-circle fw-bold shadow d-flex align-items-center justify-content-center border-0"
                             :class="step >= 3 ? 'btn-primary' : 'btn-secondary'" 
                             style="width: 40px; height:40px;">3</div>
                        
                        <div class="position-absolute top-100 start-0 translate-middle-x mt-3 text-muted fw-bold small">Dirección</div>
                        <div class="position-absolute top-100 start-50 translate-middle-x mt-3 text-muted fw-bold small">Pago</div>
                        <div class="position-absolute top-100 start-100 translate-middle-x mt-3 text-muted fw-bold small">Listo</div>
                    </div>

                    <br>

                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden mt-4">
                        <div class="card-header bg-dark text-white py-3 text-center">
                            <h5 class="m-0 fw-light">Formulario de Compra Segura</h5>
                        </div>
                        <div class="card-body p-5 bg-white">
                            <form @submit.prevent="processPayment">
                                
                                <div v-if="step === 1">
                                    <h4 class="mb-4 fw-bold text-primary border-bottom pb-2">📍 ¿Dónde enviamos tu pedido?</h4>
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <label class="form-label fw-bold text-muted">Calle y Número</label>
                                            <input v-model="form.street" type="text" class="form-control form-control-lg bg-light border-0" placeholder="Ej: Av. Alameda 45" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-muted">Ciudad</label>
                                            <input v-model="form.city" type="text" class="form-control bg-light border-0" placeholder="Ej: Alcoy" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold text-muted">C. Postal</label>
                                            <input v-model="form.zip" type="text" class="form-control bg-light border-0" placeholder="03800" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold text-muted">País</label>
                                            <select v-model="form.country" class="form-select bg-light border-0">
                                                <option value="España">España</option>
                                                <option value="Portugal">Portugal</option>
                                                <option value="Francia">Francia</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end mt-5">
                                        <button type="button" class="btn btn-primary btn-lg px-5 rounded-pill shadow fw-bold" @click="nextStep">
                                            Siguiente Paso &rarr;
                                        </button>
                                    </div>
                                </div>

                                <div v-if="step === 2">
                                    <h4 class="mb-4 fw-bold text-primary border-bottom pb-2">💳 Datos de Pago</h4>
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-muted">Titular</label>
                                            <input type="text" class="form-control bg-light border-0" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-muted">Número Tarjeta</label>
                                            <input v-model="form.card_number" type="text" class="form-control bg-light border-0" placeholder="0000 0000 0000 0000" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold text-muted">Caducidad</label>
                                            <input type="text" class="form-control bg-light border-0 text-center" placeholder="MM/AA" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold text-muted">CVV</label>
                                            <input type="password" class="form-control bg-light border-0 text-center" placeholder="123" required>
                                        </div>
                                    </div>

                                    <div class="bg-light p-4 rounded-4 mt-5 d-flex justify-content-between align-items-center border border-light">
                                        <div>
                                            <small class="text-muted text-uppercase fw-bold">Total a pagar</small>
                                            <div class="fs-2 fw-bold text-dark">{{ formatCurrency(cartStore.totalPrice) }}</div>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-lg px-5 rounded-pill shadow fw-bold" :disabled="loading">
                                            {{ loading ? 'Procesando...' : 'PAGAR AHORA' }}
                                        </button>
                                    </div>
                                    <div class="mt-3 text-start">
                                        <button type="button" class="btn btn-link text-muted text-decoration-none" @click="prevStep">
                                            &larr; Volver a dirección
                                        </button>
                                    </div>
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
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useCartStore } from '../stores/cart';
import MainLayout from '../layouts/MainLayout.vue';

const router = useRouter();
const cartStore = useCartStore();

const step = ref(1);
const loading = ref(false);

const form = ref({
    street: '',
    city: '',
    zip: '',
    country: 'España',
    card_number: ''
});

const progressWidth = computed(() => {
    return step.value === 1 ? '0%' : (step.value === 2 ? '50%' : '100%');
});

const formatCurrency = (val) => new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(val);

const nextStep = () => {
    if(!form.value.street || !form.value.city || !form.value.zip) {
        alert('Rellena todos los campos de dirección');
        return;
    }
    step.value = 2;
};

const prevStep = () => step.value = 1;

// Lógica para volver a pasos anteriores haciendo clic en el círculo
const goToStep = (targetStep) => {
    if (targetStep < step.value) {
        step.value = targetStep;
    }
};

const processPayment = async () => {
    loading.value = true;
    try {
        await cartStore.checkout(form.value);
        router.push({ name: 'order-success' });
    } catch (error) {
        console.error(error);
        alert("Error al procesar el pedido. " + (error.response?.data?.message || ''));
    } finally {
        loading.value = false;
    }
};
</script>