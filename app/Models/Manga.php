<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Manga extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'publisher',
        'genre',
        'synopsis',
        'status',
        'cover_image',
        'is_featured',
    ];

    public function volumes(): HasMany
    {
        return $this->hasMany(Volume::class)->orderBy('volume_number', 'asc');
    }
}
