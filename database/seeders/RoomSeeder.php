<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            ['A1', 'Standard', 1200000, 1, 1, 'Tersedia'],
            ['B2', 'Deluxe', 1750000, 2, 2, 'Tersedia'],
            ['C3', 'Standard', 1300000, 2, 1, 'Ditempati'],
            ['D4', 'Standard', 1250000, 1, 1, 'Tersedia'],
            ['E5', 'Deluxe', 1800000, 1, 2, 'Ditempati'],
            ['F6', 'Standard', 1350000, 2, 1, 'Tersedia'],
            ['G7', 'Deluxe', 1850000, 2, 2, 'Tersedia'],
            ['H8', 'Standard', 1400000, 1, 1, 'Ditempati'],
            ['I9', 'Deluxe', 1900000, 1, 2, 'Tersedia'],
            ['J10', 'Standard', 1450000, 2, 1, 'Tersedia'],
            ['K11', 'Deluxe', 1950000, 2, 2, 'Ditempati'],
            ['L12', 'Standard', 1500000, 3, 1, 'Tersedia'],
            ['M13', 'Deluxe', 2000000, 3, 2, 'Tersedia'],
            ['N14', 'Standard', 1550000, 3, 1, 'Ditempati'],
            ['O15', 'Deluxe', 2050000, 3, 2, 'Tersedia'],
            ['P16', 'Standard', 1600000, 1, 1, 'Tersedia'],
            ['Q17', 'Deluxe', 2100000, 2, 2, 'Ditempati'],
            ['R18', 'Standard', 1650000, 3, 1, 'Tersedia'],
            ['S19', 'Deluxe', 2150000, 3, 2, 'Tersedia'],
            ['T20', 'Standard', 1700000, 3, 1, 'Ditempati'],
        ];

        foreach ($rooms as [$number, $type, $price, $floor, $capacity, $status]) {
            Room::firstOrCreate(
                ['slug' => 'kamar-'.strtolower($number)],
                [
                    'room_number' => $number,
                    'type' => $type,
                    'monthly_price' => $price,
                    'floor' => $floor,
                    'capacity' => $capacity,
                    'status' => $status,
                    'facilities' => $type === 'Deluxe'
                        ? ['Kasur', 'Lemari', 'Meja', 'AC']
                        : ['Kasur', 'Lemari', 'Meja'],
                    'description' => "Kamar {$type} di lantai {$floor}.",
                    'featured' => in_array($number, ['A1', 'B2'], true),
                ],
            );
        }
    }
}
