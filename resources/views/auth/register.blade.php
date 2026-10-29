@extends('layouts.app')

@section('title', 'Registrasi')

@section('content')
    <div class="container page">
        <div class="auth-box card">
            <div class="card-body">
                <h1 class="h3 mb-3">Daftar TuanKost</h1>
                @include('auth._errors')

                <form method="POST" action="{{ route('register.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="name">Nama</label>
                        <input class="form-control" type="text" id="name" name="name" value="{{ old('name') }}" autocomplete="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Kata sandi (minimal 8 karakter)</label>
                        <input class="form-control" type="password" id="password" name="password" minlength="8" autocomplete="new-password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password_confirmation">Konfirmasi kata sandi</label>
                        <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required>
                    </div>
                    <button class="btn btn-primary" type="submit">Daftar</button>
                </form>

                <p class="mt-3 mb-0">Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
            </div>
        </div>
    </div>
@endsection
