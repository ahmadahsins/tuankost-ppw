@extends('layouts.app')

@section('title', 'Tambah Kamar')

@section('content')
    <div class="container page">
        <h1>Tambah Kamar</h1>
        <form class="room-form" action="{{ route('rooms.store') }}" method="POST">
            @csrf
            @include('rooms._form', ['room' => null])
        </form>
    </div>
@endsection
