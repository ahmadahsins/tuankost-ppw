@if ($errors->any())
    <div class="form-errors">
        <p>Periksa kembali data kamar:</p>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="room-form-field">
    <label for="room_number">Nomor kamar</label>
    <input id="room_number" name="room_number" type="text" value="{{ old('room_number', $room?->room_number) }}" required maxlength="20">
</div>

<div class="room-form-field">
    <label for="type">Tipe kamar</label>
    <select id="type" name="type" required>
        @foreach (['Standard', 'Deluxe'] as $type)
            <option value="{{ $type }}" @selected(old('type', $room?->type) === $type)>{{ $type }}</option>
        @endforeach
    </select>
</div>

<div class="room-form-field">
    <label for="monthly_price">Harga per bulan (Rp)</label>
    <input id="monthly_price" name="monthly_price" type="number" min="0" step="0.01" value="{{ old('monthly_price', $room?->monthly_price) }}" required>
</div>

<div class="room-form-field">
    <label for="floor">Lantai</label>
    <input id="floor" name="floor" type="number" min="1" max="255" value="{{ old('floor', $room?->floor) }}" required>
</div>

<div class="room-form-field">
    <label for="capacity">Kapasitas (orang)</label>
    <input id="capacity" name="capacity" type="number" min="1" max="255" value="{{ old('capacity', $room?->capacity ?? 1) }}" required>
</div>

<div class="room-form-field">
    <label for="status">Status</label>
    <select id="status" name="status" required>
        @foreach (['Tersedia', 'Ditempati'] as $status)
            <option value="{{ $status }}" @selected(old('status', $room?->status) === $status)>{{ $status }}</option>
        @endforeach
    </select>
</div>

<div class="room-form-field">
    <label for="facilities">Fasilitas (pisahkan dengan koma)</label>
    <input id="facilities" name="facilities" type="text" value="{{ old('facilities', $room ? implode(', ', $room->facilities ?? []) : '') }}" required>
</div>

<div class="room-form-field">
    <label for="description">Deskripsi</label>
    <textarea id="description" name="description" rows="4" required>{{ old('description', $room?->description) }}</textarea>
</div>

<div class="room-form-field">
    <label><input name="featured" type="checkbox" value="1" @checked(old('featured', $room?->featured ?? false))> Kamar unggulan</label>
</div>

<button class="button" type="submit">Simpan</button>
<a href="{{ route('rooms.index') }}">Batal</a>
