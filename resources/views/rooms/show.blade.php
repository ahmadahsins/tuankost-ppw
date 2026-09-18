@extends('layouts.app')

@section('title', 'Kamar ' . $room->room_number)

@section('content')
    <div class="container page">
        <p><a href="{{ route('rooms.index') }}">Kembali ke Daftar Kamar</a></p>

        <h1>Kamar {{ $room->room_number }}</h1>
        <ul class="room-info">
            <li>Tipe: {{ $room->type }}</li>
            <li>Harga: Rp{{ number_format($room->monthly_price, 0, ',', '.') }}/bulan</li>
            <li>Lantai: {{ $room->floor }}</li>
            <li>Kapasitas: {{ $room->capacity }} orang</li>
        </ul>

        <p>{{ $room->description }}</p>

        @if ($room->status === 'Tersedia')
            <p class="status">Kamar tersedia.</p>
        @else
            <p class="status">{{ $room->status }}</p>
        @endif

        <h2>Fasilitas</h2>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Fasilitas</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($room->facilities as $facility)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $facility }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">Belum ada fasilitas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
