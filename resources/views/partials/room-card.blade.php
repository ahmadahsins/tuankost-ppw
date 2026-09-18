<article class="room-item">
    <h3>Kamar {{ $room->room_number }}</h3>
    <p>{{ $room->type }} - Rp{{ number_format($room->monthly_price, 0, ',', '.') }}/bulan</p>
    <p>{{ $room->status }}</p>
    <a href="{{ route('rooms.show', $room->slug) }}">Lihat Detail</a>
</article>
