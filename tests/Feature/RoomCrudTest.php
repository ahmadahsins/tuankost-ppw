<?php

namespace Tests\Feature;

use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_room_can_be_created_updated_and_deleted(): void
    {
        $this->get(route('rooms.create'))->assertOk()->assertSee('Tambah Kamar');

        $this->post(route('rooms.store'), $this->roomData())
            ->assertRedirect(route('rooms.index'));

        $room = Room::where('room_number', 'U21')->firstOrFail();
        $this->assertSame('kamar-u21', $room->slug);
        $this->assertSame(['Kasur', 'Lemari'], $room->facilities);
        $this->get(route('rooms.show', $room->slug))->assertOk()->assertSee('Kamar U21');
        $this->get(route('rooms.edit', $room))->assertOk()->assertSee('Edit Kamar U21');

        $this->put(route('rooms.update', $room), $this->roomData([
            'room_number' => 'V22',
            'monthly_price' => 1600000,
            'featured' => 1,
        ]))->assertRedirect(route('rooms.index'));

        $room->refresh();
        $this->assertSame('V22', $room->room_number);
        $this->assertSame('kamar-v22', $room->slug);
        $this->assertTrue($room->featured);
        $this->get(route('rooms.show', 'kamar-u21'))->assertNotFound();
        $this->get(route('rooms.show', 'kamar-v22'))->assertOk();

        $this->delete(route('rooms.destroy', $room))->assertRedirect(route('rooms.index'));
        $this->assertDatabaseMissing('rooms', ['id' => $room->id]);
    }

    public function test_invalid_or_duplicate_room_is_rejected(): void
    {
        $this->post(route('rooms.store'), $this->roomData())->assertSessionHasNoErrors();

        $this->post(route('rooms.store'), $this->roomData())
            ->assertSessionHasErrors('room_number');

        $this->post(route('rooms.store'), $this->roomData(['room_number' => 'u21']))
            ->assertSessionHasErrors('room_number');

        $this->post(route('rooms.store'), $this->roomData([
            'room_number' => 'Bad Room',
            'monthly_price' => -1,
        ]))->assertSessionHasErrors(['room_number', 'monthly_price']);

        $this->assertDatabaseCount('rooms', 1);
    }

    private function roomData(array $overrides = []): array
    {
        return array_replace([
            'room_number' => 'U21',
            'type' => 'Standard',
            'monthly_price' => 1500000,
            'floor' => 2,
            'capacity' => 1,
            'status' => 'Tersedia',
            'facilities' => 'Kasur, Lemari',
            'description' => 'Kamar di lantai dua.',
        ], $overrides);
    }
}
