<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['sub_categories_id', 'cylinder_capacities_id', 'models_id', 'boxes_id', 'type_products_id', 'brands_id', 'name', 'description', 'code', 'image', 'count', 'link', 'reference', 'detail', 'created_at'];

    public function aditionalPictures(): HasMany
    {
        return $this->hasMany(AditionalPicturesProduct::class, 'products_id');
    }

    public function promotions()
    {
        return $this->hasMany(Promotion::class, 'products_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brands_id', 'id');
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_categories_id');
    }

    public function typeProduct()
    {
        return $this->belongsTo(TypeProduct::class, 'type_products_id');
    }

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'product_stores', 'products_id', 'stores_id');
    }

    public function model(){
        return $this->belongsTo(Modell::class, 'models_id');
    }

    // Método que define los campos que serán indexados
    public function toSearchableArray()
    {
        $array = $this->toArray();

        // Aquí puedes modificar qué campos se indexan
        // Asegúrate de que 'id' esté presente
        return $array;
    }
}
