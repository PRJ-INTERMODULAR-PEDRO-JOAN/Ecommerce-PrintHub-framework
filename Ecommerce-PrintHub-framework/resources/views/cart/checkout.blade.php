@extends('layouts.legacy')

@section('title', 'Finalizar Compra')

@section('content')
<div class="contenido-principal container py-5">
    
    <div class="row justify-content-center mb-5">
        <div class="col-md-8">
            <div class="d-flex justify-content-between position-relative align-items-center">
                <div class="position-absolute w-100 bg-light" style="height: 4px; z-index: 0; top: 15px;"></div>
                
                <div class="text-center position-relative z-1">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold shadow step-circle" id="circle-1" style="width: 35px; height: 35px;">1</div>
                    <small class="fw-bold mt-1 d-block text-primary">Envío</small>
                </div>

                <div class="text-center position-relative z-1">
                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center fw-bold shadow step-circle" id="circle-2" style="width: 35px; height: 35px;">2</div>
                    <small class="fw-bold mt-1 d-block text-muted" id="label-2">Pago</small>
                </div>

                <div class="text-center position-relative z-1">
                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center fw-bold shadow step-circle" style="width: 35px; height: 35px;">3</div>
                    <small class="fw-bold mt-1 d-block text-muted">Finalización</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-dark text-white py-3 rounded-top-4">
                    <h5 class="mb-0 text-center fw-bold" id="card-title">Datos de Envío</h5>
                </div>
                
                <div class="card-body p-5">
                    <form id="checkout-form" action="{{ route('cart.process') }}" method="POST">
                        @csrf
                        
                        <div id="step-1-content">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Dirección de Envío Completa</label>
                                <textarea id="input-address" name="address" class="form-control form-control-lg" rows="3" placeholder="Calle, Número, Piso, Ciudad, CP..." required></textarea>
                                <div class="invalid-feedback">Por favor, introduce tu dirección.</div>
                            </div>
                            <div class="alert alert-info small">
                                <i class="bi bi-truck"></i> Envío gratuito estimado en 24-48 horas.
                            </div>
                            <button type="button" class="btn btn-primary w-100 btn-lg rounded-pill" onclick="goToStep2()">
                                Continuar al Pago &rarr;
                            </button>
                        </div>

                        <div id="step-2-content" style="display: none;">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Titular de la Tarjeta</label>
                                <input type="text" class="form-control" placeholder="Nombre como aparece en la tarjeta" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Número de Tarjeta</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">💳</span>
                                    <input id="input-card" type="text" name="card_number" class="form-control" placeholder="0000 0000 0000 0000" maxlength="19" required>
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-6">
                                    <label class="form-label fw-bold">Caducidad</label>
                                    <input type="text" class="form-control" placeholder="MM/AA" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold">CVV</label>
                                    <input type="password" class="form-control" placeholder="123" maxlength="3" required>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded-3 border">
                                <span class="fs-5 text-muted">Total a Pagar:</span>
                                <span class="fs-3 fw-bold text-success">{{ $total }} €</span>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary w-50 rounded-pill" onclick="goToStep1()">
                                    &larr; Volver
                                </button>
                                <button type="submit" class="btn btn-success w-50 rounded-pill fw-bold">
                                    Pagar Ahora ✅
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function goToStep2() {
        const address = document.getElementById('input-address');
        if (!address.value.trim()) {
            alert("Por favor, rellena la dirección de envío para continuar.");
            address.focus();
            return;
        }

        // Cambiar visibilidad
        document.getElementById('step-1-content').style.display = 'none';
        document.getElementById('step-2-content').style.display = 'block';
        document.getElementById('card-title').innerText = "Pago Seguro";

        // Actualizar Stepper
        const c2 = document.getElementById('circle-2');
        c2.classList.remove('bg-secondary');
        c2.classList.add('bg-primary');
        
        const l2 = document.getElementById('label-2');
        l2.classList.remove('text-muted');
        l2.classList.add('text-primary');
    }

    function goToStep1() {
        document.getElementById('step-1-content').style.display = 'block';
        document.getElementById('step-2-content').style.display = 'none';
        document.getElementById('card-title').innerText = "Datos de Envío";

        // Revertir Stepper
        const c2 = document.getElementById('circle-2');
        c2.classList.remove('bg-primary');
        c2.classList.add('bg-secondary');

        const l2 = document.getElementById('label-2');
        l2.classList.remove('text-primary');
        l2.classList.add('text-muted');
    }
</script>
@endsection