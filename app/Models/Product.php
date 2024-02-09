<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    public function aditionalPictures(): HasMany
    {
        return $this->hasMany(AditionalPicturesProduct::class, 'products_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brands_id', 'id');
    }
}
