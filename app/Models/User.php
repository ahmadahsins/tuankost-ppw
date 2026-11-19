<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public const LEVEL_USER = 'user';

    public const LEVEL_ADMIN = 'admin';

    protected $fillable = ['name', 'email', 'password'];

    protected $attributes = ['level' => self::LEVEL_USER];

    protected $hidden = ['password', 'remember_token'];

    public function isAdmin(): bool
    {
        return $this->level === self::LEVEL_ADMIN;
    }

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }
}
