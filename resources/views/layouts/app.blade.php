<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TuanKost') | TuanKost</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <header class="site-header">
        <div class="container">
            <a class="brand" href="{{ route('home') }}">TuanKost</a>
            <nav class="main-nav">
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('rooms.index') }}">Kamar</a>
                <a href="{{ route('about') }}">Tentang</a>
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
