<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchUser extends Model
{
    use HasFactory;

    protected $fillable = ['users_id', 'stores_id', 'product_stores_id', 'created_at'];

    public $table = 'search_users';

    public function productStore()
    {
        return $this->belongsTo(ProductStore::class, 'product_stores_id');
    }
}
