<form class="room-filter" action="{{ route('rooms.search') }}" method="GET">
    <label for="type">Tipe kamar</label>
    <select id="type" name="type">
        <option value="">Semua Tipe</option>
        @foreach ($types as $option)
            <option value="{{ $option }}" @selected($type === $option)>{{ $option }}</option>
        @endforeach
    </select>

    <label for="search">Cari nomor atau tipe kamar</label>
    <input id="search" type="text" name="search" value="{{ $search }}" placeholder="Contoh: A1">
    <button type="submit">Cari</button>
</form>
