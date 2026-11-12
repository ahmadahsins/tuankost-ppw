<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function images(): HasMany
    {
        return $this->hasMany(RoomImage::class);
    }

    public function coverImage(): HasOne
    {
        return $this->hasOne(RoomImage::class)->oldestOfMany();
    }
}
