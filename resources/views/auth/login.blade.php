@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="container page">
        <div class="auth-box card">
            <div class="card-body">
                <h1 class="h3 mb-3">Login TuanKost</h1>
                @include('auth._errors')

                <form method="POST" action="{{ route('login.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Kata sandi</label>
                        <input class="form-control" type="password" id="password" name="password" autocomplete="current-password" required>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1">
                        <label class="form-check-label" for="remember">Ingat saya</label>
                    </div>
                    <button class="btn btn-primary" type="submit">Login</button>
                </form>

                <p class="mt-3 mb-0">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
            </div>
        </div>
    </div>
@endsection
