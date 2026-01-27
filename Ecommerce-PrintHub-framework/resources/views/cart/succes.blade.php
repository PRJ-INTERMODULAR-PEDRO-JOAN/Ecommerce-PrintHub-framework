@extends('layouts.legacy')

@section('title', 'Pedido Completado')

@section('content')
<div class="contenido-principal container py-5">
    
    <div class="row justify-content-center mb-5">
        <div class="col-md-8">
            <div class="d-flex justify-content-between position-relative align-items-center">
                <div class="position-absolute w-100 bg-primary" style="height: 4px; z-index: 0; top: 15px;"></div>
                
                <div class="text-center position-relative z-1">
                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold shadow step-circle" style="width: 35px; height: 35px;">✓</div>
                </div>

                <div class="text-center position-relative z-1">
                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold shadow step-circle" style="width: 35px; height: 35px;">✓</div>
                </div>

                <div class="text-center position-relative z-1">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold shadow step-circle" style="width: 35px; height: 35px;">3</div>
                    <small class="fw-bold mt-1 d-block text-primary">¡Completado!</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card border-0 shadow-lg p-5 rounded-4">
                <div class="mb-4">
                    <div style="font-size: 80px;">🎉</div>
                </div>
                <h2 class="fw-bold text-success mb-3">¡Gracias por tu compra!</h2>
                <p class="lead text-muted mb-4">
                    Tu pedido <strong>#{{ $order->id }}</strong> ha sido procesado correctamente.
                </p>
                
                <div class="bg-light p-4 rounded-3 text-start mb-4">
                    <h5 class="fw-bold">Detalles de envío:</h5>
                    <p class="mb-1"><strong>Dirección:</strong> {{ $order->shipping_address }}</p>
                    <p class="mb-1"><strong>Total Pagado:</strong> {{ $order->total_price }} €</p>
                    <p class="mb-0 text-primary"><i class="bi bi-clock"></i> Entrega estimada: 24 - 48 horas.</p>
                </div>

                <a href="{{ route('home') }}" class="btn btn-dark btn-lg rounded-pill px-5">Volver al Inicio</a>
            </div>
        </div>
    </div>
</div>
@endsection