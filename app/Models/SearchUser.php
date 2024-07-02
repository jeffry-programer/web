<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SearchUser extends Model
{
    use HasFactory;

    protected $fillable = ['users_id', 'stores_id', 'product_stores_id', 'created_at'];

    public $table = 'search_users';

    public function store(){
        return $this->BelongsTo(Store::class, 'stores_id');
    }

    public function product(){
        return $this->BelongsTo(Product::class, 'products_id');
    }
}
