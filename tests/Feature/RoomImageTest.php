<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\User;
use Database\Seeders\RoomSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RoomImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_room_photos_are_uploaded_displayed_and_cleaned_up(): void
    {
        $disk = Storage::fake('public');
        $this->seed(RoomSeeder::class);
        $room = Room::where('room_number', 'A1')->firstOrFail();
        $this->signIn();

        $this->from(route('rooms.edit', $room))->post(route('rooms.images.store', $room), [
            'images' => [
                UploadedFile::fake()->image('depan.png', 1200, 600),
                UploadedFile::fake()->image('dalam.jpg', 200, 100),
            ],
        ])->assertRedirect(route('rooms.edit', $room))->assertSessionHasNoErrors();

        $images = $room->images()->orderBy('id')->get();
        $this->assertCount(2, $images);
        $disk->assertExists($images->flatMap(fn ($image) => [$image->image_path, $image->thumbnail_path])->all());
        $this->assertSame([1000, 500], array_slice(getimagesizefromstring($disk->get($images[0]->image_path)), 0, 2));
        $this->assertSame([300, 150], array_slice(getimagesizefromstring($disk->get($images[0]->thumbnail_path)), 0, 2));
        $this->assertSame([200, 100], array_slice(getimagesizefromstring($disk->get($images[1]->thumbnail_path)), 0, 2));
        $base = 'http://127.0.0.1:8002';
        $this->get($base.'/rooms')->assertOk()
            ->assertSee($base.'/storage/'.$images[0]->thumbnail_path);
        $this->get($base.'/rooms/'.$room->slug)->assertOk()
            ->assertSee($base.'/storage/'.$images[1]->image_path);

        $this->delete(route('room-images.destroy', $images[0]))->assertRedirect();
        $disk->assertMissing([$images[0]->image_path, $images[0]->thumbnail_path]);
        $this->assertDatabaseMissing('room_images', ['id' => $images[0]->id]);

        $this->delete(route('rooms.destroy', $room))->assertRedirect();
        $disk->assertMissing([$images[1]->image_path, $images[1]->thumbnail_path]);
        $this->assertDatabaseCount('room_images', 0);
    }

    public function test_upload_requires_login_and_rejects_invalid_files(): void
    {
        $disk = Storage::fake('public');
        $this->seed(RoomSeeder::class);
        $room = Room::firstOrFail();
        $url = route('rooms.images.store', $room);

        $this->post($url)->assertRedirect('/login');
        $this->signIn();
        $this->post($url, [
            'images' => [UploadedFile::fake()->create('document.pdf', 100, 'application/pdf')],
        ])->assertSessionHasErrors('images.0');
        $this->post($url, [
            'images' => [UploadedFile::fake()->image('besar.jpg')->size(2049)],
        ])->assertSessionHasErrors('images.0');

        $this->assertDatabaseCount('room_images', 0);
        $this->assertSame([], $disk->allFiles());
    }

    private function signIn(): void
    {
        $this->actingAs(User::create([
            'name' => 'Penguji Foto',
            'email' => 'foto@example.test',
            'password' => 'password-pengujian',
        ]));
    }
}
