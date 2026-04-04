<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Restaurant App' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; }
        .navbar-brand { font-weight: bold; letter-spacing: 1px; }
        .card { box-shadow: 0 2px 8px rgba(0,0,0,0.07); border-radius: 12px; }
        .footer { background: #222; color: #fff; padding: 16px 0; text-align: center; margin-top: 48px; }
        .btn-primary, .btn-success { border-radius: 8px; }
        .table th, .table td { vertical-align: middle; }
    </style>
    @stack('head')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/">🍽️ RESTO QR</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="/menu">Menu</a></li>
                <li class="nav-item"><a class="nav-link" href="/cart">Keranjang</a></li>
                <li class="nav-item"><a class="nav-link" href="/login-simple">Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4 min-vh-80">
    @yield('content')
</main>

<footer class="footer">
    <div class="container">
        <small>&copy; {{ date('Y') }} Resto QR. All rights reserved.</small>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
