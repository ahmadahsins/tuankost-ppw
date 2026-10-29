@extends('layouts.app')

@section('title', 'Hasil Pencarian Kamar')

@section('content')
    <div class="container page">
        <h1>Hasil Pencarian Kamar</h1>
        <p><a href="{{ route('rooms.index') }}">Kembali ke Daftar Kamar</a></p>

        @include('rooms._search-form')

        @if ($rooms->total() > 0)
            <p>Ditemukan {{ $rooms->total() }} kamar{{ $search !== '' ? ' untuk "'.$search.'"' : '' }}.</p>
        @else
            <p>Kamar tidak ditemukan{{ $search !== '' ? ' untuk "'.$search.'"' : '' }}.</p>
        @endif

        @include('rooms._results')
    </div>
@endsection
