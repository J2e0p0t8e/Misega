<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'phone', 'birth_date', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $appends = ['is_adult'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birth_date' => 'date',
            'password' => 'hashed',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Determine if the user is 18 years or older.
     */
    protected function isAdult(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->birth_date ? Carbon::parse($this->birth_date)->age >= 18 : false,
        );
    }

    /**
     * Helper method to check if user is 18+.
     */
    public function isAdultUser(): bool
    {
        return $this->is_adult;
    }
}
