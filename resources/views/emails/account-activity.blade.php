<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $registered ? 'Selamat datang di TuanKost' : 'Login berhasil di TuanKost' }}</title>
</head>
<body>
    <h2>Halo, {{ $name }}.</h2>
    @if ($registered)
        <p>Akun TuanKost Anda berhasil didaftarkan pada {{ $occurredAt }}.</p>
        <p>Selamat datang! Anda dapat mengakses informasi dan pengelolaan kamar melalui aplikasi.</p>
    @else
        <p>Akun TuanKost Anda berhasil login pada {{ $occurredAt }}.</p>
        <p>Jika Anda tidak melakukan login ini, segera ubah kata sandi akun Anda.</p>
    @endif
    <p><a href="{{ url('/dashboard') }}">Buka TuanKost</a></p>
    <p>Salam,<br>Tim TuanKost</p>
</body>
</html>
