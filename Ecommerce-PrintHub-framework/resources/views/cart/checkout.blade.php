@extends('layouts.legacy')

@section('title', 'Finalizar Compra')

@section('content')
    <div class="row justify-content-center">
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10 col-md-12">
                
                <h2 class="text-center mb-5 fw-bold text-dark">Finalizar Pedido</h2>

                <div class="position-relative mx-auto mb-5" style="max-width: 600px;">
                    <div class="progress" style="height: 4px; background-color: #e9ecef;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 0%; transition: width 0.4s ease;" id="progressBar"></div>
                    </div>
                    
                    <div class="position-absolute top-0 start-0 translate-middle btn btn-sm btn-primary rounded-circle fw-bold shadow d-flex align-items-center justify-content-center border-0" style="width: 40px; height:40px;">1</div>
                    <div class="position-absolute top-0 start-50 translate-middle btn btn-sm btn-secondary rounded-circle fw-bold shadow d-flex align-items-center justify-content-center border-0" id="step2-indicator" style="width: 40px; height:40px;">2</div>
                    <div class="position-absolute top-0 start-100 translate-middle btn btn-sm btn-secondary rounded-circle fw-bold shadow d-flex align-items-center justify-content-center border-0" id="step3-indicator" style="width: 40px; height:40px;">3</div>
                    
                    <div class="position-absolute top-100 start-0 translate-middle-x mt-3 text-muted fw-bold small text-nowrap">
                        Dirección
                    </div>
                    <div class="position-absolute top-100 start-50 translate-middle-x mt-3 text-muted fw-bold small text-nowrap">
                        Pago
                    </div>
                    <div class="position-absolute top-100 start-100 translate-middle-x mt-3 text-muted fw-bold small text-nowrap">
                        Listo
                    </div>
                </div>

                <br>

                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-dark text-white py-3 text-center">
                        <h5 class="m-0 fw-light">Formulario de Compra Segura</h5>
                    </div>
                    <div class="card-body p-5 bg-white">
                        <form id="checkoutForm" action="{{ route('cart.process') }}" method="POST">
                            @csrf
                            
                            <div id="step1" class="step-content">
                                <h4 class="mb-4 fw-bold text-primary border-bottom pb-2">📍 ¿Dónde enviamos tu pedido?</h4>
                                
                                <div class="row g-4">
                                    <div class="col-12">
                                        <label class="form-label fw-bold text-muted">Calle y Número</label>
                                        <input type="text" id="street" name="street" class="form-control form-control-lg bg-light border-0" placeholder="Ej: Av. Alameda 45, 2º B" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">Ciudad</label>
                                        <input type="text" id="city" name="city" class="form-control bg-light border-0" placeholder="Ej: Alcoy" required>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-bold text-muted">C. Postal</label>
                                        <input type="text" id="zip" name="zip" class="form-control bg-light border-0" placeholder="03800" required>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-bold text-muted">País</label>
                                        <select name="country" id="country" class="form-select bg-light border-0">
                                            <option value="España">España</option>
                                            <option value="Portugal">Portugal</option>
                                            <option value="Francia">Francia</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-5">
                                    <button type="button" class="btn btn-primary btn-lg px-5 rounded-pill shadow fw-bold" onclick="nextStep(1)">
                                        Siguiente Paso &rarr;
                                    </button>
                                </div>
                            </div>

                            <div id="step2" class="step-content" style="display:none;">
                                <h4 class="mb-4 fw-bold text-primary border-bottom pb-2">💳 Datos de Pago</h4>
                                
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">Titular de la Tarjeta</label>
                                        <input type="text" class="form-control bg-light border-0" placeholder="Como aparece en la tarjeta" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">Número de Tarjeta</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0 text-muted"><i class="bi bi-credit-card-2-front"></i></span>
                                            <input type="text" id="card_number" name="card_number" class="form-control bg-light border-0" placeholder="0000 0000 0000 0000" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-muted">Caducidad</label>
                                        <input type="text" class="form-control bg-light border-0 text-center" placeholder="MM/AA" maxlength="5" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-muted">CVV</label>
                                        <input type="password" class="form-control bg-light border-0 text-center" placeholder="123" maxlength="3" required>
                                    </div>
                                    
                                    <div class="col-md-4 d-flex align-items-end justify-content-center text-muted">
                                        <small>🔒 Pago SSL Seguro</small>
                                    </div>
                                </div>

                                <div class="bg-light p-4 rounded-4 mt-5 d-flex justify-content-between align-items-center border border-light">
                                    <div>
                                        <small class="text-muted text-uppercase fw-bold">Total a pagar</small>
                                        <div class="fs-2 fw-bold text-dark">{{ $total }} €</div>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-lg px-5 rounded-pill shadow fw-bold">
                                        PAGAR AHORA
                                    </button>
                                </div>

                                <div class="mt-3 text-start">
                                    <button type="button" class="btn btn-link text-muted text-decoration-none" onclick="prevStep(2)">
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
</div>

<script>
    function nextStep(currentStep) {
        if (currentStep === 1) {
            // Validar campos
            const street = document.getElementById('street').value.trim();
            const city = document.getElementById('city').value.trim();
            const zip = document.getElementById('zip').value.trim();

            if (!street || !city || !zip) {
                alert("⚠️ Por favor, rellena todos los campos de la dirección.");
                return;
            }

            // Cambiar vista
            document.getElementById('step1').style.display = 'none';
            document.getElementById('step2').style.display = 'block';

            // Actualizar barra
            document.getElementById('progressBar').style.width = '50%';
            
            const c2 = document.getElementById('step2-indicator');
            c2.classList.remove('btn-secondary');
            c2.classList.add('btn-primary');
        }
    }

    function prevStep(currentStep) {
        if (currentStep === 2) {
            document.getElementById('step2').style.display = 'none';
            document.getElementById('step1').style.display = 'block';
            
            document.getElementById('progressBar').style.width = '0%';
            
            const c2 = document.getElementById('step2-indicator');
            c2.classList.remove('btn-primary');
            c2.classList.add('btn-secondary');
        }
    }
</script>
@endsection