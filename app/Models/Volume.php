<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Volume extends Model
{
    use HasFactory;

    protected $fillable = [
        'manga_id',
        'volume_number',
        'price',
        'stock',
        'cover_image_url',
        'isbn',
    ];

    protected $casts = [
        'price' => 'float',
        'volume_number' => 'integer',
        'stock' => 'integer',
    ];

    public function manga(): BelongsTo
    {
        return $this->belongsTo(Manga::class);
    }
}
