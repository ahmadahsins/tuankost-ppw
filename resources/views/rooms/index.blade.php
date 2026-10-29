@extends('layouts.app')

@section('title', 'Daftar Kamar')

@section('content')
    <div class="container page">
        <h1>Daftar Kamar</h1>

        <p><a class="button" href="{{ route('rooms.create') }}">Tambah Kamar</a></p>

        @if (session('success'))
            <p class="notice">{{ session('success') }}</p>
        @endif

        @include('rooms._search-form', ['search' => ''])
        @include('rooms._results')

        <div class="room-stats">
            <h2>Statistik Kamar</h2>
            <p>Total kamar: {{ $totalRooms }}</p>
            <p>Total harga semua kamar: Rp{{ number_format($totalPrice, 0, ',', '.') }}</p>
            <p>Harga tertinggi: Rp{{ number_format($highestPrice ?? 0, 0, ',', '.') }}</p>
            <p>Harga terendah: Rp{{ number_format($lowestPrice ?? 0, 0, ',', '.') }}</p>
        </div>
    </div>
@endsection
