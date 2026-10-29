<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TuanKost') | TuanKost</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <header class="site-header">
        <div class="container">
            <a class="brand" href="{{ route('home') }}">TuanKost</a>
            <nav class="main-nav">
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('about') }}">Tentang</a>
                @auth
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <a href="{{ route('rooms.index') }}">Kamar</a>
                    <form class="logout-form" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Daftar</a>
                @endauth
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; 2026 TuanKost</p>
        </div>
    </footer>
</body>

</html>
