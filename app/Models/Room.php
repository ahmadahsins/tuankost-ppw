<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';

    protected $fillable = [
        'slug',
        'room_number',
        'type',
        'monthly_price',
        'floor',
        'capacity',
        'status',
        'facilities',
        'description',
        'featured',
    ];

    protected function casts(): array
    {
        return [
            'monthly_price' => 'decimal:2',
            'facilities' => 'array',
            'featured' => 'boolean',
        ];
    }
}
