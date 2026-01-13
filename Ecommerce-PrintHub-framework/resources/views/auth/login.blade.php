@extends('layouts.login')

@section('title', 'Login')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/loginStyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/formstyle.css') }}">
@endpush

@section('content')
<?php
session_start();
//require_once '../includes/json_connect.php';

// Si ya está logueado, redirigir al perfil
if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* -------------------------------------------------
       1. VALIDAR reCAPTCHA con Google
    -------------------------------------------------- */

    $secretKey = "6LfmChosAAAAAHaTmvsOsHr5ml9SEN6EzIZstsXZ"; // ← PON TU SECRET KEY AQUÍ
    $captchaResponse = $_POST['g-recaptcha-response'];

    $verify = file_get_contents(
        "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captchaResponse"
    );

    $captchaSuccess = json_decode($verify);

    if (!$captchaSuccess->success) {
        $error = "Verifica el reCAPTCHA.";
    } else {

        /* -------------------------------------------------
           2. PROCESAR LOGIN SOLO SI EL CAPTCHA ES VÁLIDO
        -------------------------------------------------- */

        $username = trim($_POST['username']);
        $password = $_POST['password'];

        // Buscar usuario
        //$user = findUserByUsername($username);

        // Verificar contraseña
        if ($user && password_verify($password, $user['contrasenya'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['nom_usuari'];

            setcookie('user_id', $user['id'], time() + 3600, "/");

            header("Location: profile.php");
            exit;
        } else {
            $error = "Usuario o contraseña incorrectos.";
        }
    }
}
?>

<div class="container">
    <h2>🔐 Iniciar Sesión</h2>

    <!-- Span para errores -->
    <span class="error" id="formError" style="display:none;"></span>

    <form method="POST" action="{{ route('login') }}" class="login-form">
        @csrf
        <input type="text" name="username" id="username" placeholder="Nombre de usuario" required>
        <input type="password" name="password" id="password" placeholder="Contraseña" required>

        <div class="g-recaptcha" data-sitekey="6LfmChosAAAAAO1KhMNCFkQiKGDuwLH6Ss4kc5Ns"></div>

        <button type="submit">Entrar</button>
    </form>

    <p>¿No tienes cuenta? <a href="register.php">Regístrate</a></p>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/login-validation.js') }}"></script>
@endpush