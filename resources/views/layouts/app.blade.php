<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SheStitch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --pink:#f5d0dc; --beige:#f8f1e9; --black:#111; --white:#fff; }
        body { background:var(--beige); color:var(--black); font-family: "Segoe UI", sans-serif; }
        .navbar { background:var(--white); box-shadow:0 4px 14px rgba(0,0,0,.08); }
        .btn-pink { background:#e99ab3; color:#fff; border:none; }
        .btn-pink:hover { background:#d985a0; color:#fff; transform:translateY(-1px); transition:.25s; }
        .hero { min-height:70vh; background:linear-gradient(rgba(0,0,0,.35), rgba(0,0,0,.35)), url('https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&w=1500&q=80') center/cover; color:#fff; }
        .card { border:none; box-shadow:0 6px 20px rgba(0,0,0,.08); border-radius:16px; }
        .badge-discount { background:#111; }
        .footer { background:#111; color:#fff; }
        .pagination svg { width: 5px; height: 5px; }
        .pagination { font-size: 0.40rem; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">SheStitch</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto gap-lg-2">
                @auth
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('shop.index') }}">Categories</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('size.chart') }}">Size Chart</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('favorites.index') }}">Favorites</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('cart.index') }}">Cart</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('order.create') }}">Contact</a></li>
                    <li class="nav-item">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="nav-link btn btn-link">Logout</button>
    </form>
</li>
                @endauth
                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

@if(session('success'))
    <div class="container mt-3">
        <div class="alert alert-success">{{ session('success') }}</div>
    </div>
@endif

@yield('content')

<footer class="footer mt-5 py-4">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
        <p class="mb-2 mb-md-0">© {{ date('Y') }} SheStitch - Design Your Dream Dress</p>
        <div class="d-flex gap-3">
            <i class="fab fa-facebook"></i><i class="fab fa-instagram"></i><i class="fab fa-pinterest"></i><i class="fab fa-youtube"></i>
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/site.js') }}"></script>
</body>
</html>
