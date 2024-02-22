<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStore extends Model
{
    use HasFactory;

    protected $fillable = ['products_id','stores_id','amount', 'price'];

    public function product(){
        return $this->belongsTo(Product::class, 'products_id', 'id');
    }

    public function store(){
        return $this->belongsTo(Store::class, 'stores_id', 'id');
    }
}
