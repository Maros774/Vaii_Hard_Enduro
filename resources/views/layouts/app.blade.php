<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Hard Enduro') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Načítanie Vite súborov -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
<!-- Hlavička -->
<header class="header-bar bg-dark text-white p-3">
    <div class="container">
        <h1 class="text-center mb-3">Hard Enduro</h1>
        <nav class="nav-bar d-flex justify-content-center">
            <a class="nav-link fs-4 mx-3 {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Domov</a>
            <a class="nav-link fs-4 mx-3 {{ request()->routeIs('about.index') ? 'active' : '' }}" href="{{ route('about.index') }}">O nás</a>

            <a class="nav-link fs-4 mx-3 {{ request()->routeIs('motorcycles') ? 'active' : '' }}" href="{{ route('motorcycles') }}">Motocykle</a>
            <a class="nav-link fs-4 mx-3 {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Kontakt</a>
            <a class="nav-link fs-4 mx-3 {{ request()->routeIs('posts.*') ? 'active' : '' }}" href="{{ route('posts.index') }}">Príspevky</a>
        </nav>
    </div>
</header>

<!-- Obsah -->
<main class="container my-4">
    @yield('content')
</main>

<!-- Pätka -->
<footer class="footer-bar bg-dark text-white text-center p-3">
    <p>© 2024 Hard Enduro Skupina</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
