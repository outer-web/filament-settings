<?php

declare(strict_types=1);

namespace Outerweb\FilamentSettings\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Outerweb\FilamentSettings\Tests\Fixtures\Factories\UserFactory;

#[UseFactory(UserFactory::class)]
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array{
     *   email_verified_at: 'datetime',
     *   password: 'hashed',
     * }
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
