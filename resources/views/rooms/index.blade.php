@extends('layouts.app')

@section('title', 'Daftar Kamar')

@section('content')
    <div class="container page">
        <h1>Daftar Kamar</h1>

        <p><a class="button" href="{{ route('rooms.create') }}">Tambah Kamar</a></p>

        @if (session('success'))
            <p class="notice">{{ session('success') }}</p>
        @endif

        <form class="room-filter" action="{{ route('rooms.index') }}" method="GET">
            <label for="type">Tipe kamar</label>
            <select id="type" name="type">
                <option value="">Semua Tipe</option>
                @foreach ($types as $option)
                    <option value="{{ $option }}" @selected($type === $option)>{{ $option }}</option>
                @endforeach
            </select>

            <label for="search">Cari nomor kamar</label>
            <input id="search" type="text" name="search" value="{{ $search }}" placeholder="Contoh: A1">
            <button type="submit">Cari</button>
        </form>

        <table class="room-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kamar</th>
                    <th>Tipe</th>
                    <th>Harga/bulan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($rooms as $room)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $room->room_number }}</td>
                    <td>{{ $room->type }}</td>
                    <td>Rp{{ number_format($room->monthly_price, 0, ',', '.') }}</td>
                    <td>{{ $room->status }}</td>
                    <td class="room-actions">
                        <a href="{{ route('rooms.show', $room->slug) }}">Detail</a>
                        <a href="{{ route('rooms.edit', $room) }}">Edit</a>
                        <form action="{{ route('rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kamar ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Kamar tidak ditemukan.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div class="room-stats">
            <h2>Statistik Kamar</h2>
            <p>Total kamar: {{ $totalRooms }}</p>
            <p>Total harga semua kamar: Rp{{ number_format($totalPrice, 0, ',', '.') }}</p>
            <p>Harga tertinggi: Rp{{ number_format($highestPrice ?? 0, 0, ',', '.') }}</p>
            <p>Harga terendah: Rp{{ number_format($lowestPrice ?? 0, 0, ',', '.') }}</p>
        </div>
    </div>
@endsection
