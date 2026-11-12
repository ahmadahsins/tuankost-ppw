<table class="room-table">
    <thead>
        <tr>
            <th>No.</th>
            <th>Kamar</th>
                    <th>Foto</th>
            <th>Tipe</th>
            <th>Harga/bulan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    @forelse ($rooms as $room)
        <tr>
            <td>{{ $rooms->firstItem() + $loop->index }}</td>
            <td>{{ $room->room_number }}</td>
                    <td>
                        @if ($room->coverImage)
                            <a href="{{ route('rooms.show', $room->slug) }}">
                                <img src="{{ asset('storage/'.$room->coverImage->thumbnail_path) }}"
                                     width="64" alt="Foto kamar {{ $room->room_number }}" loading="lazy">
                            </a>
                        @else
                            <span aria-label="Belum ada foto">—</span>
                        @endif
                    </td>
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
                    <td colspan="7">Kamar tidak ditemukan.</td>
        </tr>
    @endforelse
    </tbody>
</table>

@if ($rooms->hasPages())
    <nav class="room-pagination" aria-label="Halaman daftar kamar">
        @if ($rooms->onFirstPage())
            <span>Sebelumnya</span>
        @else
            <a href="{{ $rooms->previousPageUrl() }}">Sebelumnya</a>
        @endif

        <span>Halaman {{ $rooms->currentPage() }} dari {{ $rooms->lastPage() }}</span>

        @if ($rooms->hasMorePages())
            <a href="{{ $rooms->nextPageUrl() }}">Berikutnya</a>
        @else
            <span>Berikutnya</span>
        @endif
    </nav>
@endif
