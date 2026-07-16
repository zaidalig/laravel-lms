<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Learn Online') - LearnHub</title>
    <link rel="stylesheet" href="{{ asset_cdn('fontawesome', 'vendor/fontawesome/css/all.min.css') }}">
    <link href="{{ asset_cdn('bootstrap_css', 'vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="public-body">
<nav class="navbar navbar-expand-lg public-nav sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}"><i class="fa-solid fa-graduation-cap text-primary me-2"></i>LearnHub</a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#publicNav"><span class="navbar-toggler-icon"></span></button>
        <div id="publicNav" class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home')?'active':'' }}" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('public.courses*')?'active':'' }}" href="{{ route('public.courses') }}">Courses</a></li>
                @auth
                <li class="nav-item"><a class="nav-link" href="{{ route('my.courses') }}">My Learning</a></li>
                <li class="nav-item"><a class="btn btn-primary btn-sm rounded-pill px-3 ms-lg-2" href="{{ route('dashboard') }}">Dashboard</a></li>
                @else
                <li class="nav-item"><a class="btn btn-primary btn-sm rounded-pill px-3 ms-lg-2" href="{{ route('login') }}">Login</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
<main>
    @if(session('success'))<div class="container mt-3"><div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div></div>@endif
    @if(session('error'))<div class="container mt-3"><div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div></div>@endif
    @yield('content')
</main>
<footer class="public-footer py-4 mt-5">
    <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
        <span class="fw-semibold"><i class="fa-solid fa-graduation-cap me-1"></i>LearnHub</span>
        <span class="small">Learn anything, anywhere. &copy; {{ date('Y') }}</span>
    </div>
</footer>
<script src="{{ asset_cdn('bootstrap_js', 'vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
