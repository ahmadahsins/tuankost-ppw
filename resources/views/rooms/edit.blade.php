@extends('layouts.app')

@section('title', 'Edit Kamar ' . $room->room_number)

@section('content')
    <div class="container page">
        <h1>Edit Kamar {{ $room->room_number }}</h1>
        <form class="room-form" action="{{ route('rooms.update', $room) }}" method="POST">
            @csrf
            @method('PUT')
            @include('rooms._form')
        </form>
    </div>
@endsection
