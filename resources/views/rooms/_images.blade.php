<section class="mt-4">
    <h2>Foto Kamar</h2>

    @if (session('success'))
        <p class="notice">{{ session('success') }}</p>
    @endif

    <div class="d-flex flex-wrap gap-3">
        @forelse ($room->images as $image)
            <div>
                <a href="{{ asset('storage/'.$image->image_path) }}" target="_blank" rel="noopener">
                    <img src="{{ asset('storage/'.$image->thumbnail_path) }}"
                         alt="Foto kamar {{ $room->room_number }}" width="120" class="img-thumbnail" loading="lazy">
                </a>
                @if ($canManage)
                    <form action="{{ route('room-images.destroy', $image) }}" method="POST"
                          onsubmit="return confirm('Hapus foto ini?')" class="mt-1">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" type="submit">Hapus foto</button>
                    </form>
                @endif
            </div>
        @empty
            <p>Belum ada foto kamar.</p>
        @endforelse
    </div>

    @if ($canManage)
        <form action="{{ route('rooms.images.store', $room) }}" method="POST" enctype="multipart/form-data" class="room-form mt-3">
            @csrf
            <label for="room-images" class="form-label">Pilih foto kamar</label>
            <input id="room-images" class="form-control" type="file" name="images[]" multiple required
                   accept="image/jpeg,image/png,image/gif,image/webp" aria-describedby="room-images-help">
            <p id="room-images-help" class="form-text">Maksimal 5 foto per unggahan, 2 MB dan 4000 × 4000 piksel per foto. Format JPEG, PNG, GIF, atau WebP.</p>
            <button class="btn btn-primary" type="submit">Unggah foto</button>
        </form>
    @endif
</section>
