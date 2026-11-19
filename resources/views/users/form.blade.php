@extends('layouts.app')

@php
    $editing = $user->exists;
    $editingSelf = auth()->user()->is($user);
    $title = $editing ? 'Edit Pengguna' : 'Tambah Pengguna';
@endphp

@section('title', $title)

@section('content')
    <div class="container page">
        <h1>{{ $title }}</h1>

        @if ($errors->any())
            <ul class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form class="room-form" method="POST" action="{{ $editing ? route('users.update', $user) : route('users.store') }}">
            @csrf
            @if ($editing)
                @method('PUT')
            @endif
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
            @unless ($editingSelf)
                <div class="mb-3">
                    <label for="level" class="form-label">Level</label>
                    <select id="level" name="level" class="form-select" required>
                        <option value="user" @selected(old('level', $user->level) === 'user')>User</option>
                        <option value="admin" @selected(old('level', $user->level) === 'admin')>Admin</option>
                    </select>
                </div>
            @endunless
            <div class="mb-3">
                <label for="password" class="form-label">Kata sandi</label>
                <input id="password" name="password" type="password" class="form-control" autocomplete="new-password"
                    minlength="8" @required(! $editing)>
                @if ($editing)
                    <div class="form-text">Kosongkan jika kata sandi tidak diubah.</div>
                @endif
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi kata sandi</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control"
                    autocomplete="new-password" minlength="8" @required(! $editing)>
            </div>
            <button class="btn btn-primary" type="submit">Simpan</button>
            <a href="{{ route('users.index') }}">Batal</a>
        </form>
    </div>
@endsection
