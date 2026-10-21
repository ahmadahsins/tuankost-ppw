@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <div class="container page">
        <h1>TuanKost</h1>
        <p>Sistem informasi manajemen rumah kos.</p>
        <p><a class="button" href="{{ route('rooms.index') }}">Lihat Kamar</a></p>

        <h2>Kamar Unggulan</h2>
        <div class="room-list">
            @forelse ($featuredRooms as $room)
                @include('partials.room-card', ['room' => $room])
            @empty
                <p>Belum ada kamar unggulan.</p>
            @endforelse
        </div>

        <h2 class="section-title">5 Kamar Terbaru</h2>
        <div class="room-list">
            @forelse ($latestRooms as $room)
                @include('partials.room-card', ['room' => $room])
            @empty
                <p>Belum ada kamar.</p>
            @endforelse
        </div>
    </div>
@endsection
