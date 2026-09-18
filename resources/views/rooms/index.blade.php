@extends('layouts.app')

@section('title', 'Daftar Kamar')

@section('content')
    <div class="container page">
        <h1>Daftar Kamar</h1>

        <div class="room-list">
            @forelse ($rooms as $room)
                @include('partials.room-card', ['room' => $room])
            @empty
                <p>Belum ada kamar.</p>
            @endforelse
        </div>
    </div>
@endsection
