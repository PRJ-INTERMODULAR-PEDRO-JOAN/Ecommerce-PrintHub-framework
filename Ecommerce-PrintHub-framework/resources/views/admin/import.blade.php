@extends('layouts.legacy')

@section('title', 'Importar Productes')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/importarStyle.css') }}">
@endpush

@section('content')
<div class="import-wrapper" style="padding: 50px; text-align: center;">
    <h2>Administració: Importar Productes (Excel)</h2>
    
    @if($errors->any())
        <div style="color: red; margin-bottom: 20px;">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="import-form">
        @csrf
        <div class="file-input-container" style="margin: 20px 0;">
            <input type="file" name="file" required accept=".xlsx, .xls, .csv">
        </div>
        
        <button type="submit" class="btn-primary" style="padding: 10px 20px; cursor: pointer;">
            Pujar i Importar
        </button>
    </form>
</div>
@endsection