@extends('layouts.legacy')

@section('title', 'Pedido Completado')

@section('content')
<div class="contenido-principal container py-5 row justify-content-center>
>
    
    <div class="row justify-content-center mb-5">
        <div class="col-lg-9">
            <div class="position-relative mx-5">
                <div class="progress" style="height: 3px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%;"></div>
                </div>
                <div class="position-absolute top-0 start-0 translate-middle btn btn-sm btn-success rounded-circle fw-bold d-flex align-items-center justify-content-center" style="width: 35px; height:35px;">✓</div>
                <div class="position-absolute top-0 start-50 translate-middle btn btn-sm btn-success rounded-circle fw-bold d-flex align-items-center justify-content-center" style="width: 35px; height:35px;">✓</div>
                <div class="position-absolute top-0 start-100 translate-middle btn btn-sm btn-success rounded-circle fw-bold d-flex align-items-center justify-content-center" style="width: 35px; height:35px;">✓</div>
            </div>
            <div class="text-center mt-3 text-success fw-bold">PROCESO FINALIZADO</div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card border-0 shadow-lg p-5 rounded-4 bg-white">
                <div class="mb-4 display-1">🎉</div>
                
                <h1 class="fw-bold text-success mb-3">¡Gracias por tu compra!</h1>
                <p class="lead text-muted mb-5">
                    Tu pedido <strong>#{{ $order->id }}</strong> se ha registrado correctamente en nuestra base de datos.
                </p>
                
                <div class="bg-light p-4 rounded-4 text-start d-inline-block w-100 border">
                    <h5 class="fw-bold border-bottom pb-2 mb-3">Resumen del Pedido</h5>
                    
                    <div class="row mb-2">
                        <div class="col-4 text-muted">Total:</div>
                        <div class="col-8 fw-bold text-dark">{{ $order->total_price }} €</div>
                    </div>
                    
                    <div class="row mb-2">
                        <div class="col-4 text-muted">Dirección:</div>
                        <div class="col-8 text-dark">{{ $order->shipping_address }}</div>
                    </div>
                    
                    <div class="row mb-0">
                        <div class="col-4 text-muted">Método:</div>
                        <div class="col-8">
                            <span class="badge bg-primary">Tarjeta</span>
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <a href="{{ route('home') }}" class="btn btn-dark btn-lg rounded-pill px-5 shadow-sm">Volver al Inicio</a>
                </div>
            </div>
        </div>
    </div>
</div>
<br>

<style>
    @media (min-width: 992px) {
        .container { margin-left: 280px !important; }
    }
</style>
@endsection