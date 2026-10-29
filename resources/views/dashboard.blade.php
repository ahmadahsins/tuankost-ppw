@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container page">
        <h1>Dashboard</h1>
        <p>Selamat datang, {{ auth()->user()->name }}.</p>
        <p>Email: {{ auth()->user()->email }}</p>
        <a class="button" href="{{ route('rooms.index') }}">Kelola Kamar</a>
    </div>
@endsection
