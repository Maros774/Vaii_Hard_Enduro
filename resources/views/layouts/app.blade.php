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
            <a class="nav-link fs-4 mx-3 {{ request()->routeIs('motorcycles.index') ? 'active' : '' }}" href="{{ route('motorcycles.index') }}">Motocykle</a>
            <a class="nav-link fs-4 mx-3 {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Kontakt</a>
            <a class="nav-link fs-4 mx-3 {{ request()->routeIs('posts.*') ? 'active' : '' }}" href="{{ route('posts.index') }}">Príspevky</a>
        </nav>
    </div>
</header>

<!-- Flash správy -->
<div class="container mt-3">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
</div>

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

<!-- Validácia na strane klienta -->
<script>
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', e => {
            const inputs = form.querySelectorAll('input[required], textarea[required]');
            let valid = true;

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    valid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!valid) {
                e.preventDefault();
                alert('Vyplňte všetky povinné polia.');
            }
        });
    });
</script>
</body>
</html>
