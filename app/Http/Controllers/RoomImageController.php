<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\ImageManager;
use RuntimeException;
use Throwable;

class RoomImageController extends Controller
{
    public function store(Request $request, Room $room): RedirectResponse
    {
        $request->validate([
            'images' => ['required', 'array', 'min:1', 'max:5'],
            'images.*' => ['required', 'image', 'mimes:jpeg,png,gif,webp', 'max:2048', 'dimensions:max_width=4000,max_height=4000'],
        ], [
            'images.required' => 'Pilih minimal satu foto.',
            'images.max' => 'Maksimal lima foto per unggahan.',
            'images.*.image' => 'Berkas harus berupa gambar.',
            'images.*.mimes' => 'Format foto harus JPEG, PNG, GIF, atau WebP.',
            'images.*.max' => 'Ukuran setiap foto maksimal 2 MB.',
            'images.*.dimensions' => 'Dimensi setiap foto maksimal 4000 × 4000 piksel.',
        ]);

        $manager = new ImageManager(Driver::class, decodeAnimation: false);
        $disk = Storage::disk('public');
        $paths = [];

        try {
            DB::transaction(function () use ($request, $room, $manager, $disk, &$paths) {
                foreach ($request->file('images') as $file) {
                    $filename = now()->format('Ymd_His').'_'.Str::random(12).'.jpg';
                    $imagePath = 'room_images/'.$filename;
                    $thumbnailPath = 'room_images_thumbnail/'.$filename;
                    $image = $manager->decode($file)->scaleDown(width: 1000);
                    $original = $image->encode(new JpegEncoder(quality: 85, strip: true));
                    $thumbnail = $image->scaleDown(width: 300)->encode(new JpegEncoder(quality: 70, strip: true));

                    $paths[] = $imagePath;
                    $paths[] = $thumbnailPath;

                    if (! $disk->put($imagePath, (string) $original) || ! $disk->put($thumbnailPath, (string) $thumbnail)) {
                        throw new RuntimeException('Foto kamar gagal disimpan.');
                    }

                    $room->images()->create([
                        'image_path' => $imagePath,
                        'thumbnail_path' => $thumbnailPath,
                    ]);
                }
            });
        } catch (Throwable $exception) {
            $disk->delete($paths);

            if ($exception instanceof DecoderException) {
                throw ValidationException::withMessages(['images' => 'Salah satu foto tidak dapat dibaca.']);
            }

            throw $exception;
        }

        return back()->with('success', 'Foto kamar berhasil diunggah.');
    }

    public function destroy(RoomImage $roomImage): RedirectResponse
    {
        $roomImage->delete();
        Storage::disk('public')->delete([$roomImage->image_path, $roomImage->thumbnail_path]);

        return back()->with('success', 'Foto kamar berhasil dihapus.');
    }
}
